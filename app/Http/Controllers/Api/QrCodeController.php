<?php

namespace App\Http\Controllers\Api;

use App\Models\QrCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeFacade;


class QrCodeController extends Controller
{

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:vcard,email,sms,url,social,text,wifi,event,facebook,twitter,linkedin,instagram,youtube,tiktok,pinterest,snapchat',
            'data' => 'required',
            'settings' => 'nullable'
        ]);

        $data = is_string($validated['data']) 
            ? json_decode($validated['data'], true) 
            : $validated['data'];
        
        $settings = is_string($validated['settings'] ?? '{}')
            ? json_decode($validated['settings'] ?? '{}', true)
            : ($validated['settings'] ?? []);

        $content = $this->buildContent($validated['type'], $data);
        $svg = $this->buildQrCode($content, $settings);

        // Generate a unique filename
        $filename = 'qr_' . time() . '_' . uniqid() . '.svg';
        
        // Define the storage path
        $storagePath = 'qrcodes/' . $filename;
        $publicPath = public_path('storage/' . $storagePath);
        
        // Ensure directory exists
        $directory = dirname($publicPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Save the SVG file
        file_put_contents($publicPath, $svg);
        
        // Create database record
        $qrCodeRecord = QrCode::create([
            'user_id' => auth()->id(), // This will be null for guests
            'name' => $this->generateQrName($validated['type'], $data),
            'type' => $validated['type'],
            'data' => $data,
            'settings' => $settings,
            'file_path' => 'storage/' . $storagePath,
            'is_dynamic' => false
        ]);

        // Convert SVG to data URL for immediate display
        $svgDataUrl = 'data:image/svg+xml;charset=utf-8,' . rawurlencode($svg);

        return response()->json([
            'success' => true,
            'file' => $svgDataUrl,
            'type' => 'svg',
            'record' => $qrCodeRecord, // Optional: return the saved record
            'message' => 'QR Code generated and saved successfully'
        ]);
    }

    // private function buildContent($type, $data)
    // {
    //     // Ensure data is an array
    //     if (!is_array($data)) {
    //         $data = [];
    //     }
        
    //     switch ($type) {
    //         case 'vcard':
    //             return $this->buildVCard($data);
    //         case 'email':
    //             // Add null coalescing for email fields
    //             $email = $data['email'] ?? '';
    //             $subject = $data['subject'] ?? '';
    //             $body = $data['body'] ?? '';
    //             return "mailto:{$email}?subject=" . urlencode($subject) . "&body=" . urlencode($body);
    //         case 'sms':
    //             $number = $data['number'] ?? '';
    //             $message = $data['message'] ?? '';
    //             return "smsto:{$number}:" . urlencode($message);
    //         case 'url':
    //             return $data['url'] ?? '';
    //         case 'social':
    //             return $this->buildSocialContent($data);
    //         case 'text':  // Add this case
    //             $text = $data['text'] ?? '';
    //             // Trim and check if empty
    //             $text = trim($text);
    //             if (empty($text)) {
    //                 throw new \InvalidArgumentException('Text content cannot be empty');
    //             }
    //             return $text;
    //         case 'wifi':  // Add wifi case if you have it
    //             return $this->buildWifiContent($data);
    //         case 'event':  // Add event case if you have it
    //             return $this->buildEventContent($data);
    //         default:
    //             return '';
    //     }
    // }

    private function buildContent($type, $data)
{
    // Ensure data is an array
    if (!is_array($data)) {
        $data = [];
    }
    
    // Handle individual social platform types
    $socialPlatforms = ['facebook', 'twitter', 'linkedin', 'instagram', 'youtube', 'tiktok', 'pinterest', 'snapchat'];
    
    if (in_array($type, $socialPlatforms)) {
        // Add the platform to the data array
        $data['platform'] = $type;
        return $this->buildSocialContent($data);
    }
    
    switch ($type) {
        case 'vcard':
            return $this->buildVCard($data);
        case 'email':
            $email = $data['email'] ?? '';
            $subject = $data['subject'] ?? '';
            $body = $data['body'] ?? '';
            return "mailto:{$email}?subject=" . urlencode($subject) . "&body=" . urlencode($body);
        case 'sms':
            $number = $data['number'] ?? '';
            $message = $data['message'] ?? '';
            return "smsto:{$number}:" . urlencode($message);
        case 'url':
            return $data['url'] ?? '';
        case 'social':
            return $this->buildSocialContent($data);
        case 'text':
            $text = $data['text'] ?? '';
            $text = trim($text);
            if (empty($text)) {
                throw new \InvalidArgumentException('Text content cannot be empty');
            }
            return $text;
        case 'wifi':
            return $this->buildWifiContent($data);
        case 'event':
            return $this->buildEventContent($data);
        default:
            return '';
    }
}

    /**
     * Generate a name for the QR code based on type and data
     */
    private function generateQrName($type, $data)
    {
        switch ($type) {
            case 'vcard':
                $firstName = $data['firstname'] ?? '';
                $lastName = $data['lastname'] ?? '';
                return trim("{$firstName} {$lastName}") ?: 'Contact Card';
                
            case 'email':
                return $data['email'] ?? 'Email QR Code';
                
            case 'sms':
                return 'SMS to ' . ($data['number'] ?? 'Unknown');
                
            case 'url':
                $url = $data['url'] ?? '';
                return $url ? substr($url, 0, 30) . '...' : 'URL QR Code';
                
            case 'wifi':
                return 'WiFi: ' . ($data['ssid'] ?? 'Network');
                
            case 'event':
                return $data['title'] ?? 'Calendar Event';
                
            case 'facebook':
            case 'twitter':
            case 'linkedin':
            case 'instagram':
            case 'youtube':
            case 'tiktok':
            case 'pinterest':
            case 'snapchat':
                return ucfirst($type) . ': ' . ($data['username'] ?? 'Profile');
                
            case 'text':
                $text = $data['text'] ?? '';
                return $text ? substr($text, 0, 30) . '...' : 'Text QR Code';
                
            default:
                return ucfirst($type) . ' QR Code';
        }
    }

    private function buildVCard($data)
    {
        $vcard = "BEGIN:VCARD\n";
        $vcard .= "VERSION:3.0\n";
        
        // Name fields - match your form's casing
        $firstname = $data['firstName'] ?? $data['firstname'] ?? '';
        $lastname = $data['lastName'] ?? $data['lastname'] ?? '';
        
        if (!empty($firstname) || !empty($lastname)) {
            $vcard .= "N:{$lastname};{$firstname}\n";
            $vcard .= "FN:" . trim("{$firstname} {$lastname}") . "\n";
        }
        
        // Phone numbers - match your form fields
        if (!empty($data['phoneWork'] ?? '')) {
            $vcard .= "TEL;TYPE=WORK,VOICE:{$data['phoneWork']}\n";
        }
        if (!empty($data['phoneMobile'] ?? '')) {
            $vcard .= "TEL;TYPE=CELL:{$data['phoneMobile']}\n";
        }
        if (!empty($data['phoneHome'] ?? '')) {
            $vcard .= "TEL;TYPE=HOME,VOICE:{$data['phoneHome']}\n";
        }
        if (!empty($data['fax'] ?? '')) {
            $vcard .= "TEL;TYPE=FAX:{$data['fax']}\n";
        }
        
        // Email
        if (!empty($data['email'] ?? '')) {
            $vcard .= "EMAIL:{$data['email']}\n";
        }
        
        // Organization/Company
        if (!empty($data['organization'] ?? '')) {
            $vcard .= "ORG:{$data['organization']}\n";
        }
        
        // Job title
        if (!empty($data['title'] ?? '')) {
            $vcard .= "TITLE:{$data['title']}\n";
        }
        
        // Address - build from components
        $addressParts = [];
        if (!empty($data['addressStreet'] ?? '')) $addressParts[] = $data['addressStreet'];
        if (!empty($data['addressCity'] ?? '')) $addressParts[] = $data['addressCity'];
        if (!empty($data['addressState'] ?? '')) $addressParts[] = $data['addressState'];
        if (!empty($data['addressZip'] ?? '')) $addressParts[] = $data['addressZip'];
        if (!empty($data['addressCountry'] ?? '')) $addressParts[] = $data['addressCountry'];
        
        if (!empty($addressParts)) {
            $address = implode(';', $addressParts);
            $vcard .= "ADR;TYPE=WORK:;;{$address}\n";
        }
        
        // Website
        if (!empty($data['website'] ?? '')) {
            $website = $data['website'];
            // Add https:// if missing
            if (!preg_match('/^https?:\/\//', $website)) {
                $website = 'http://' . $website;
            }
            $vcard .= "URL:{$website}\n";
        }
        
        // Birthday
        if (!empty($data['birthday'] ?? '')) {
            $vcard .= "BDAY:{$data['birthday']}\n";
        }
        
        // Note
        if (!empty($data['note'] ?? '')) {
            $vcard .= "NOTE:{$data['note']}\n";
        }
        
        // Social Profiles (add as custom fields or URLs)
        if (!empty($data['socialProfiles'] ?? [])) {
            foreach ($data['socialProfiles'] as $profile) {
                if (!empty($profile['url'])) {
                    $vcard .= "URL;TYPE={$profile['type']}:{$profile['url']}\n";
                }
            }
        }
        
        $vcard .= "END:VCARD";
        
        return $vcard;
    }

    private function buildQrCode($content, $settings)
    {
        $qrCode = QrCodeFacade::format('svg')   // ✅ SWITCHED TO SVG
            ->size($settings['size'] ?? 300)
            ->margin($settings['margin'] ?? 1)
            ->encoding('UTF-8')
            ->errorCorrection('H');

        // Apply color (SVG supports it)
        if (!empty($settings['color'])) {
            $qrCode->color(
                $settings['color']['r'] ?? 0,
                $settings['color']['g'] ?? 0,
                $settings['color']['b'] ?? 0
            );
        }

        if (!empty($settings['background_color'])) {
            $qrCode->backgroundColor(
                $settings['background_color']['r'] ?? 255,
                $settings['background_color']['g'] ?? 255,
                $settings['background_color']['b'] ?? 255
            );
        }

        return $qrCode->generate($content);
    }

    // private function buildSocialContent($data)
    // {
    //     $platform = $data['platform'] ?? '';
    //     $username = $data['username'] ?? '';
    //     $url = $data['url'] ?? '';
        
    //     // If URL is directly provided, use it
    //     if (!empty($url)) {
    //         return $url;
    //     }
        
    //     // Otherwise build URL from platform and username
    //     switch ($platform) {
    //         case 'facebook':
    //             return "https://facebook.com/{$username}";
    //         case 'twitter':
    //             return "https://twitter.com/{$username}";
    //         case 'instagram':
    //             return "https://instagram.com/{$username}";
    //         case 'linkedin':
    //             return "https://linkedin.com/in/{$username}";
    //         case 'youtube':
    //             return "https://youtube.com/@{$username}";
    //         case 'tiktok':
    //             return "https://tiktok.com/@{$username}";
    //         case 'pinterest':
    //             return "https://pinterest.com/{$username}";
    //         case 'snapchat':
    //             return "https://snapchat.com/add/{$username}";
    //         default:
    //             return '';
    //     }
    // }

    private function buildSocialContent($data)
{
    // Log the incoming data for debugging (remove in production)
    \Log::info('Social data received:', $data);
    
    // Case 1: If URL is directly provided (from a combined social form)
    if (!empty($data['url'] ?? '')) {
        return $data['url'];
    }
    
    // Case 2: If platform and username are provided (from individual social forms)
    $platform = $data['platform'] ?? '';
    $username = $data['username'] ?? $data['handle'] ?? $data['profile'] ?? '';
    
    // Case 3: If it's from a multi-social form with profiles array
    if (!empty($data['profiles'] ?? []) && is_array($data['profiles'])) {
        // For now, just use the first profile
        $firstProfile = $data['profiles'][0] ?? [];
        if (!empty($firstProfile['url'])) {
            return $firstProfile['url'];
        }
        if (!empty($firstProfile['platform']) && !empty($firstProfile['username'])) {
            return $this->buildSocialUrl($firstProfile['platform'], $firstProfile['username']);
        }
    }
    
    // If we have both platform and username, build the URL
    if (!empty($platform) && !empty($username)) {
        return $this->buildSocialUrl($platform, $username);
    }
    
    // If we have only a username, assume it's for the platform specified in the type
    if (!empty($username) && !empty($data['type'])) {
        return $this->buildSocialUrl($data['type'], $username);
    }
    
    // If we have a direct link field
    if (!empty($data['link'] ?? '')) {
        return $data['link'];
    }
    
    // If we have a custom field
    if (!empty($data['custom'] ?? '')) {
        return $data['custom'];
    }
    
    // Log the error for debugging
    \Log::error('Could not build social content from data:', $data);
    
    // Throw exception instead of returning empty string
    throw new \InvalidArgumentException('Social media profile information is incomplete. Please provide a username or URL.');
}


private function buildSocialUrl($platform, $username)
{
    // Clean username (remove @ if present)
    $username = ltrim($username, '@');
    
    // Platform-specific URL formats
    $platforms = [
        'facebook' => "https://facebook.com/{$username}",
        'fb' => "https://facebook.com/{$username}",
        'twitter' => "https://twitter.com/{$username}",
        'x' => "https://twitter.com/{$username}",
        'instagram' => "https://instagram.com/{$username}",
        'ig' => "https://instagram.com/{$username}",
        'linkedin' => "https://linkedin.com/in/{$username}",
        'linkedin-profile' => "https://linkedin.com/in/{$username}",
        'youtube' => "https://youtube.com/@{$username}",
        'yt' => "https://youtube.com/@{$username}",
        'tiktok' => "https://tiktok.com/@{$username}",
        'pinterest' => "https://pinterest.com/{$username}",
        'snapchat' => "https://snapchat.com/add/{$username}",
        'sc' => "https://snapchat.com/add/{$username}",
        'whatsapp' => "https://wa.me/{$username}",
        'telegram' => "https://t.me/{$username}",
        'github' => "https://github.com/{$username}",
        'git' => "https://github.com/{$username}",
    ];
    
    $platform = strtolower($platform);
    
    if (isset($platforms[$platform])) {
        return $platforms[$platform];
    }
    
    // If platform not recognized, try to use as-is
    return $username;
}
    private function buildWifiContent($data)
    {
        $ssid = $data['ssid'] ?? '';
        $password = $data['password'] ?? '';
        $encryption = $data['encryption'] ?? 'WPA';
        $hidden = isset($data['hidden']) && $data['hidden'] ? 'true' : 'false';
        
        if (empty($ssid)) {
            throw new \InvalidArgumentException('WiFi SSID cannot be empty');
        }
        
        return "WIFI:T:{$encryption};S:{$ssid};P:{$password};H:{$hidden};";
    }

    private function buildEventContent($data)
    {
        $title = $data['title'] ?? '';
        $start = $data['start'] ?? '';
        $end = $data['end'] ?? '';
        $location = $data['location'] ?? '';
        $description = $data['description'] ?? '';
        
        if (empty($title)) {
            throw new \InvalidArgumentException('Event title cannot be empty');
        }
        
        $event = "BEGIN:VEVENT\n";
        $event .= "SUMMARY:{$title}\n";
        if (!empty($start)) $event .= "DTSTART:{$start}\n";
        if (!empty($end)) $event .= "DTEND:{$end}\n";
        if (!empty($location)) $event .= "LOCATION:{$location}\n";
        if (!empty($description)) $event .= "DESCRIPTION:{$description}\n";
        $event .= "END:VEVENT";
        
        return $event;
    }

    public function history(Request $request)
    {
        $qrCodes = QrCode::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => $qrCodes
        ]);
    }

    public function show($id)
    {
        $qrCode = QrCode::where('user_id', auth()->id())
            ->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $qrCode
        ]);
    }

    /**
     * Download a previously saved QR code
     */
    public function download($id)
    {
        $qrCode = QrCode::where('user_id', auth()->id())
            ->findOrFail($id);
        
        $filePath = public_path($qrCode->file_path);
        
        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }
        
        return response()->download(
            $filePath, 
            basename($qrCode->file_path),
            ['Content-Type' => 'image/svg+xml']
        );
    }

    /**
     * Delete a QR code
     */
    public function destroy($id)
    {
        $qrCode = QrCode::where('user_id', auth()->id())
            ->findOrFail($id);
        
        // Delete the file
        $filePath = public_path($qrCode->file_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        // Delete the record
        $qrCode->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'QR Code deleted successfully'
        ]);
    }

}
