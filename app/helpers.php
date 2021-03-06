<?php

use Krucas\Settings\Facades\Settings;
use SimpleSoftwareIO\SMS\Facades\SMS;
use Snowfire\Beautymail\Beautymail;

/**
 * Global helpers file with misc functions
 */

if (!function_exists('app_name')) {
    /**
     * Helper to grab the application name
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (!function_exists('access')) {
    /**
     * Access (lol) the Access:: facade as a simple function
     */
    function access()
    {
        return app('access');
    }
}

if (!function_exists('history')) {
    /**
     * Access the history facade anywhere
     */
    function history()
    {
        return app('history');
    }
}

if (!function_exists('gravatar')) {
    /**
     * Access the gravatar helper
     */
    function gravatar()
    {
        return app('gravatar');
    }
}

if (!function_exists('getFallbackLocale')) {
    /**
     * Get the fallback locale
     *
     * @return \Illuminate\Foundation\Application|mixed
     */
    function getFallbackLocale()
    {
        return config('app.fallback_locale');
    }
}

if (!function_exists('getLanguageBlock')) {

    /**
     * Get the language block with a fallback
     *
     * @param $view
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function getLanguageBlock($view, $data = [])
    {
        $components = explode("lang", $view);
        $current = $components[0] . "lang." . app()->getLocale() . "." . $components[1];
        $fallback = $components[0] . "lang." . getFallbackLocale() . "." . $components[1];

        if (view()->exists($current)) {
            return view($current, $data);
        } else {
            return view($fallback, $data);
        }
    }
}

/**
 * Formats a phone number
 *
 * Returns a human readable phone number.
 *
 * @param string $number
 * @param string $country_code
 * @return string
 */
function formatPhoneNumber($number, $country_code = false)
{
    $formatted = "(" . substr($number, 0, 3) . ") " . substr($number, 3, 3) . "-" . substr($number, 6);
    if ($country_code) $formatted = '+' . $country_code . ' ' . $formatted;
    return $formatted;
}

/**
 * Generate an API Secret
 *
 * Generates a unique API secret
 *
 * @param int $min_length
 * @param int $max_length
 * @return mixed|string
 */
function generateApiSecret($min_length = 12, $max_length = 64)
{
    do {
        $length = (int)rand($min_length, $max_length);
        $token = strtoupper(Illuminate\Support\Str::random($length)); // Generate a token with the chosen length
        if (strpos($token, 'O') !== false) $token = str_replace('O', '0', $token);
        $secret_exists = \App\Models\Access\User\User::where('api_secret', $token)->first(); // Get any accounts where that token is
    } while ($secret_exists);
    return $token;
}

/**
 * Generate a Verification Token
 *
 * Generates a unique token for phone and email verifications.
 *
 * @param int $min_length
 * @param int $max_length
 * @return mixed|string
 */
function generateVerificationToken($min_length = 3, $max_length = 6)
{
    do {
        $exists = false;
        $length = (int)rand($min_length, $max_length);
        $token = strtoupper(Illuminate\Support\Str::random($length)); // Generate a token with the chosen length
        if (strpos($token, 'O') !== false) $token = str_replace('O', '0', $token);
        $email_exist = App\Http\Models\API\Email::where('verification_token', $token)->first(); // Get any emails with that token
        $phone_exists = App\Http\Models\API\MobilePhone::where('verification_token', $token)->first(); // Get any phones with that token
        if (!empty($email_exist) || !empty($phone_exists)) $exists = true;
    } while ($exists);
    return $token;
}

/**
 * Verifies a resource with token
 *
 * Verifies phone numbers and emails via the token passed in.
 *
 * @param $token
 * @return bool
 */
function verifyToken($token)
{
    $email = App\Http\Models\API\Email::where('verification_token', $token)->first(); // Get any emails with that token
    $phone = App\Http\Models\API\MobilePhone::where('verification_token', $token)->first(); // Get any phones with that token

    if (!empty($email) && !empty($phone)) {
        abort(500, 'Duplicate Token Exception!');
    } elseif (!empty($email)) {
        $email->verified = true;
        $email->save();
        return $email;
    } elseif (!empty($phone)) {
        $phone->verified = true;
        $phone->save();
        return $phone;
    } else {
        return false;
    }
}

/**
 * Returns an array of countries
 *
 * This is used to pre-seed the database with production data.
 *
 * @return array
 */
function countryList()
{
    $now = Carbon\Carbon::now('utc')->toDateTimeString();

    return [
        ['label' => 'Afghanistan', 'code' => 'AFG', 'abbreviation' => 'AF', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Albania', 'code' => 'ALB', 'abbreviation' => 'AL', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Algeria', 'code' => 'DZA', 'abbreviation' => 'DZ', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'American Samoa', 'code' => 'ASM', 'abbreviation' => 'AS', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Andorra', 'code' => 'AND', 'abbreviation' => 'AD', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Angola', 'code' => 'AGO', 'abbreviation' => 'AO', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Anguilla', 'code' => 'AIA', 'abbreviation' => 'AI', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Antarctica', 'code' => 'ATA', 'abbreviation' => 'AQ', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Antigua and Barbuda', 'code' => 'ATG', 'abbreviation' => 'AG', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Argentina', 'code' => 'ARG', 'abbreviation' => 'AR', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Armenia', 'code' => 'ARM', 'abbreviation' => 'AM', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Aruba', 'code' => 'ABW', 'abbreviation' => 'AW', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Australia', 'code' => 'AUS', 'abbreviation' => 'AU', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Austria', 'code' => 'AUT', 'abbreviation' => 'AT', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Azerbaijan', 'code' => 'AZE', 'abbreviation' => 'AZ', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Bahamas', 'code' => 'BHS', 'abbreviation' => 'BS', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Bahrain', 'code' => 'BHR', 'abbreviation' => 'BH', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Bangladesh', 'code' => 'BGD', 'abbreviation' => 'BD', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Barbados', 'code' => 'BRB', 'abbreviation' => 'BB', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Belarus', 'code' => 'BLR', 'abbreviation' => 'BY', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Belgium', 'code' => 'BEL', 'abbreviation' => 'BE', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Belize', 'code' => 'BLZ', 'abbreviation' => 'BZ', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Benin', 'code' => 'BEN', 'abbreviation' => 'BJ', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Bermuda', 'code' => 'BMU', 'abbreviation' => 'BM', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Bhutan', 'code' => 'BTN', 'abbreviation' => 'BT', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Bolivia', 'code' => 'BOL', 'abbreviation' => 'BO', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Bosnia-Herzegovina', 'code' => 'BIH', 'abbreviation' => 'BA', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Botswana', 'code' => 'BWA', 'abbreviation' => 'BW', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Bouvet Island', 'code' => 'BVT', 'abbreviation' => 'BV', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Brazil', 'code' => 'BRA', 'abbreviation' => 'BR', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'British Indian Ocean Territory', 'code' => 'IOT', 'abbreviation' => 'IO', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Brunei Darussalam', 'code' => 'BRN', 'abbreviation' => 'BN', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Bulgaria', 'code' => 'BGR', 'abbreviation' => 'BG', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Burkina Faso', 'code' => 'BFA', 'abbreviation' => 'BF', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Burundi', 'code' => 'BDI', 'abbreviation' => 'BI', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Cambodia', 'code' => 'KHM', 'abbreviation' => 'KH', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Cameroon', 'code' => 'CMR', 'abbreviation' => 'CM', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Canada', 'code' => 'CAN', 'abbreviation' => 'CA', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Cape Verde', 'code' => 'CPV', 'abbreviation' => 'CV', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Cayman Islands', 'code' => 'CYM', 'abbreviation' => 'KY', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Central African Republic', 'code' => 'CAF', 'abbreviation' => 'CF', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Chad', 'code' => 'TCD', 'abbreviation' => 'TD', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Chile', 'code' => 'CHL', 'abbreviation' => 'CL', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'China', 'code' => 'CHN', 'abbreviation' => 'CN', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Christmas Island', 'code' => 'CXR', 'abbreviation' => 'CX', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Cocos (Keeling) Islands', 'code' => 'CCK', 'abbreviation' => 'CC', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Colombia', 'code' => 'COL', 'abbreviation' => 'CO', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Comoros', 'code' => 'COM', 'abbreviation' => 'KM', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Congo', 'code' => 'COG', 'abbreviation' => 'CG', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Congo, Democratic Republic of', 'code' => 'COD', 'abbreviation' => 'CD', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Cook Islands', 'code' => 'COK', 'abbreviation' => 'CK', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Costa Rica', 'code' => 'CRI', 'abbreviation' => 'CR', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Cote D\'Ivoire', 'code' => 'CIV', 'abbreviation' => 'CI', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Croatia (Hrvatska)', 'code' => 'HRV', 'abbreviation' => 'HR', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Cuba', 'code' => 'CUB', 'abbreviation' => 'CU', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Cyprus', 'code' => 'CYP', 'abbreviation' => 'CY', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Czech Republic', 'code' => 'CZE', 'abbreviation' => 'CZ', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Denmark', 'code' => 'DNK', 'abbreviation' => 'DK', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Djibouti', 'code' => 'DJI', 'abbreviation' => 'DJ', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Dominica', 'code' => 'DMA', 'abbreviation' => 'DM', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Dominican Republic', 'code' => 'DOM', 'abbreviation' => 'DO', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'East Timor', 'code' => 'TMP', 'abbreviation' => 'TP', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Ecuador', 'code' => 'ECU', 'abbreviation' => 'EC', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Egypt', 'code' => 'EGY', 'abbreviation' => 'EG', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'El Salvador', 'code' => 'SLV', 'abbreviation' => 'SV', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Equatorial Guinea', 'code' => 'GNQ', 'abbreviation' => 'GQ', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Eritrea', 'code' => 'ERI', 'abbreviation' => 'ER', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Estonia', 'code' => 'EST', 'abbreviation' => 'EE', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Ethiopia', 'code' => 'ETH', 'abbreviation' => 'ET', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Falkland Islands (Malvinas)', 'code' => 'FLK', 'abbreviation' => 'FK', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Faroe Islands', 'code' => 'FRO', 'abbreviation' => 'FO', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Fiji', 'code' => 'FJI', 'abbreviation' => 'FJ', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Finland', 'code' => 'FIN', 'abbreviation' => 'FI', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'France', 'code' => 'FRA', 'abbreviation' => 'FR', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'French Guiana', 'code' => 'GUF', 'abbreviation' => 'GF', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'French Polynesia', 'code' => 'PYF', 'abbreviation' => 'PF', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'French Southern Territories', 'code' => 'ATF', 'abbreviation' => 'TF', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Gabon', 'code' => 'GAB', 'abbreviation' => 'GA', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Gambia', 'code' => 'GMB', 'abbreviation' => 'GM', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Georgia', 'code' => 'GEO', 'abbreviation' => 'GE', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Germany', 'code' => 'DEU', 'abbreviation' => 'DE', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Ghana', 'code' => 'GHA', 'abbreviation' => 'GH', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Gibraltar', 'code' => 'GIB', 'abbreviation' => 'GI', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Great Britain (England, Scotland, Wales)', 'code' => 'GBR', 'abbreviation' => 'GB', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Greece', 'code' => 'GRC', 'abbreviation' => 'GR', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Greenland', 'code' => 'GRL', 'abbreviation' => 'GL', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Grenada', 'code' => 'GRD', 'abbreviation' => 'GD', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Guadeloupe', 'code' => 'GLP', 'abbreviation' => 'GP', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Guam', 'code' => 'GUM', 'abbreviation' => 'GU', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Guatemala', 'code' => 'GTM', 'abbreviation' => 'GT', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Guinea', 'code' => 'GIN', 'abbreviation' => 'GN', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Guinea-Bissau', 'code' => 'GNB', 'abbreviation' => 'GW', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Guyana', 'code' => 'GUY', 'abbreviation' => 'GY', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Haiti', 'code' => 'HTI', 'abbreviation' => 'HT', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Heard Island and McDonald Islands', 'code' => 'HMD', 'abbreviation' => 'HM', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Honduras', 'code' => 'HND', 'abbreviation' => 'HN', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Hong Kong SAR of PRC', 'code' => 'HKG', 'abbreviation' => 'HK', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Hungary', 'code' => 'HUN', 'abbreviation' => 'HU', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Iceland', 'code' => 'ISL', 'abbreviation' => 'IS', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'India', 'code' => 'IND', 'abbreviation' => 'IN', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Indonesia', 'code' => 'IDN', 'abbreviation' => 'ID', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Iran', 'code' => 'IRN', 'abbreviation' => 'IR', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Iraq', 'code' => 'IRQ', 'abbreviation' => 'IQ', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Ireland', 'code' => 'IRL', 'abbreviation' => 'IE', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Israel', 'code' => 'ISR', 'abbreviation' => 'IL', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Italy', 'code' => 'ITA', 'abbreviation' => 'IT', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Jamaica', 'code' => 'JAM', 'abbreviation' => 'JM', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Japan', 'code' => 'JPN', 'abbreviation' => 'JP', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Jordan', 'code' => 'JOR', 'abbreviation' => 'JO', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Kazakhstan', 'code' => 'KAZ', 'abbreviation' => 'KZ', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Kenya', 'code' => 'KEN', 'abbreviation' => 'KE', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Kiribati', 'code' => 'KIR', 'abbreviation' => 'KI', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Republic of Korea (South Korea)', 'code' => 'KOR', 'abbreviation' => 'KR', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Korea, Democratic People\'s Republic (North Korea)', 'code' => 'PRK', 'abbreviation' => 'KP', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Kosovo', 'code' => 'UNK', 'abbreviation' => 'XK', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Kuwait', 'code' => 'KWT', 'abbreviation' => 'KW', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Kyrgyzstan', 'code' => 'KGZ', 'abbreviation' => 'KG', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Lao People\'s Democratic Republic', 'code' => 'LAO', 'abbreviation' => 'LA', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Latvia', 'code' => 'LVA', 'abbreviation' => 'LV', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Lebanon', 'code' => 'LBN', 'abbreviation' => 'LB', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Lesotho', 'code' => 'LSO', 'abbreviation' => 'LS', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Liberia', 'code' => 'LBR', 'abbreviation' => 'LR', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Libyan Arab Jamahiriya', 'code' => 'LBY', 'abbreviation' => 'LY', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Liechtenstein', 'code' => 'LIE', 'abbreviation' => 'LI', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Lithuania', 'code' => 'LTU', 'abbreviation' => 'LT', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Luxembourg', 'code' => 'LUX', 'abbreviation' => 'LU', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Macao SAR of PRC (Macau)', 'code' => 'MAC', 'abbreviation' => 'MO', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Macedonia', 'code' => 'MKD', 'abbreviation' => 'MK', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Madagascar', 'code' => 'MDG', 'abbreviation' => 'MG', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Malawi', 'code' => 'MWI', 'abbreviation' => 'MW', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Malaysia', 'code' => 'MYS', 'abbreviation' => 'MY', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Maldives', 'code' => 'MDV', 'abbreviation' => 'MV', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Mali', 'code' => 'MLI', 'abbreviation' => 'ML', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Malta', 'code' => 'MLT', 'abbreviation' => 'MT', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Marshall Islands', 'code' => 'MHL', 'abbreviation' => 'MH', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Martinique', 'code' => 'MTQ', 'abbreviation' => 'MQ', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Mauritania', 'code' => 'MRT', 'abbreviation' => 'MR', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Mauritius', 'code' => 'MUS', 'abbreviation' => 'MU', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Mayotte', 'code' => 'MYT', 'abbreviation' => 'YT', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Mexico', 'code' => 'MEX', 'abbreviation' => 'MX', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Micronesia, Federated States of', 'code' => 'FSM', 'abbreviation' => 'FM', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Monaco', 'code' => 'MCO', 'abbreviation' => 'MC', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Mongolia', 'code' => 'MNG', 'abbreviation' => 'MN', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Montenegro', 'code' => 'MNE', 'abbreviation' => 'ME', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Montserrat', 'code' => 'MSR', 'abbreviation' => 'MS', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Morocco', 'code' => 'MAR', 'abbreviation' => 'MA', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Mozambique', 'code' => 'MOZ', 'abbreviation' => 'MZ', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Myanmar', 'code' => 'MMR', 'abbreviation' => 'MM', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Namibia', 'code' => 'NAM', 'abbreviation' => 'NA', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Nauru', 'code' => 'NRU', 'abbreviation' => 'NR', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Nepal', 'code' => 'NPL', 'abbreviation' => 'NP', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Netherlands', 'code' => 'NLD', 'abbreviation' => 'NL', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Netherlands Antilles', 'code' => 'ANT', 'abbreviation' => 'AN', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'New Caledonia', 'code' => 'NCL', 'abbreviation' => 'NC', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'New Zealand', 'code' => 'NZL', 'abbreviation' => 'NZ', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Nicaragua', 'code' => 'NIC', 'abbreviation' => 'NI', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Niger', 'code' => 'NER', 'abbreviation' => 'NE', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Nigeria', 'code' => 'NGA', 'abbreviation' => 'NG', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Niue', 'code' => 'NIU', 'abbreviation' => 'NU', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Norfolk Island', 'code' => 'NFK', 'abbreviation' => 'NF', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Northern Mariana Islands', 'code' => 'MNP', 'abbreviation' => 'MP', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Norway', 'code' => 'NOR', 'abbreviation' => 'NO', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Oman', 'code' => 'OMN', 'abbreviation' => 'OM', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Pakistan', 'code' => 'PAK', 'abbreviation' => 'PK', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Palau', 'code' => 'PLW', 'abbreviation' => 'PW', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Panama', 'code' => 'PAN', 'abbreviation' => 'PA', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Papua New Guinea', 'code' => 'PNG', 'abbreviation' => 'PG', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Paraguay', 'code' => 'PRY', 'abbreviation' => 'PY', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Peru', 'code' => 'PER', 'abbreviation' => 'PE', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Philippines', 'code' => 'PHL', 'abbreviation' => 'PH', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Pitcairn', 'code' => 'PCN', 'abbreviation' => 'PN', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Poland', 'code' => 'POL', 'abbreviation' => 'PL', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Portugal', 'code' => 'PRT', 'abbreviation' => 'PT', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Puerto Rico', 'code' => 'PRI', 'abbreviation' => 'PR', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Qatar', 'code' => 'QAT', 'abbreviation' => 'QA', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Republic of Moldova', 'code' => 'MDA', 'abbreviation' => 'MD', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Reunion', 'code' => 'REU', 'abbreviation' => 'RE', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Romania', 'code' => 'ROM', 'abbreviation' => 'RO', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Russia', 'code' => 'RUS', 'abbreviation' => 'RU', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Rwanda', 'code' => 'RWA', 'abbreviation' => 'RW', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Saint Helena', 'code' => 'SHN', 'abbreviation' => 'SH', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Saint Kitts and Nevis', 'code' => 'KNA', 'abbreviation' => 'KN', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Saint Lucia', 'code' => 'LCA', 'abbreviation' => 'LC', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Saint Pierre and Miquelon', 'code' => 'SPM', 'abbreviation' => 'PM', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Saint Vincent and the Grenadines', 'code' => 'VCT', 'abbreviation' => 'VC', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Samoa', 'code' => 'WSM', 'abbreviation' => 'WS', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'San Marino', 'code' => 'SMR', 'abbreviation' => 'SM', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Sao Tome and Principe', 'code' => 'STP', 'abbreviation' => 'ST', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Saudi Arabia', 'code' => 'SAU', 'abbreviation' => 'SA', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Senegal', 'code' => 'SEN', 'abbreviation' => 'SN', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Serbia', 'code' => 'SRB', 'abbreviation' => 'RS', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Seychelles', 'code' => 'SYC', 'abbreviation' => 'SC', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Sierra Leone', 'code' => 'SLE', 'abbreviation' => 'SL', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Singapore', 'code' => 'SGP', 'abbreviation' => 'SG', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Slovakia', 'code' => 'SVK', 'abbreviation' => 'SK', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Slovenia', 'code' => 'SVN', 'abbreviation' => 'SI', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Solomon Islands', 'code' => 'SLB', 'abbreviation' => 'SB', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Somalia', 'code' => 'SOM', 'abbreviation' => 'SO', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'South Africa', 'code' => 'ZAF', 'abbreviation' => 'ZA', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'South Georgia and South Sandwich Islands', 'code' => 'SGS', 'abbreviation' => 'GS', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Spain', 'code' => 'ESP', 'abbreviation' => 'ES', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Sri Lanka', 'code' => 'LKA', 'abbreviation' => 'LK', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Sudan', 'code' => 'SDN', 'abbreviation' => 'SD', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Suriname', 'code' => 'SUR', 'abbreviation' => 'SR', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Svalbard and Jan Mayen', 'code' => 'SJM', 'abbreviation' => 'SJ', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Swaziland', 'code' => 'SWZ', 'abbreviation' => 'SZ', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Sweden', 'code' => 'SWE', 'abbreviation' => 'SE', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Switzerland', 'code' => 'CHE', 'abbreviation' => 'CH', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Syria', 'code' => 'SYR', 'abbreviation' => 'SY', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Taiwan', 'code' => 'TWN', 'abbreviation' => 'TW', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Tajikistan', 'code' => 'TJK', 'abbreviation' => 'TJ', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Tanzania, United Republic of', 'code' => 'TZA', 'abbreviation' => 'TZ', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Thailand', 'code' => 'THA', 'abbreviation' => 'TH', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Togo', 'code' => 'TGO', 'abbreviation' => 'TG', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Tokelau', 'code' => 'TKL', 'abbreviation' => 'TK', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Tonga', 'code' => 'TON', 'abbreviation' => 'TO', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Trinidad and Tobago', 'code' => 'TTE', 'abbreviation' => 'TT', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Tunisia', 'code' => 'TUN', 'abbreviation' => 'TN', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Turkey', 'code' => 'TUR', 'abbreviation' => 'TR', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Turkmenistan', 'code' => 'TKM', 'abbreviation' => 'TM', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Turks and Caicos Islands', 'code' => 'TCA', 'abbreviation' => 'TC', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Tuvalu', 'code' => 'TUV', 'abbreviation' => 'TV', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Uganda', 'code' => 'UGA', 'abbreviation' => 'UG', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Ukraine', 'code' => 'UKR', 'abbreviation' => 'UA', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'United Arab Emirates', 'code' => 'ARE', 'abbreviation' => 'AE', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'United States', 'code' => 'USA', 'abbreviation' => 'US', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'United States Minor Outlying Islands', 'code' => 'UMI', 'abbreviation' => 'UM', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Uruguay', 'code' => 'URY', 'abbreviation' => 'UY', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Uzbekistan', 'code' => 'UZB', 'abbreviation' => 'UZ', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Vanuatu', 'code' => 'VUT', 'abbreviation' => 'VU', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Vatican City State', 'code' => 'VAT', 'abbreviation' => 'VA', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Venezuela', 'code' => 'VEN', 'abbreviation' => 'VE', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Vietnam', 'code' => 'VNM', 'abbreviation' => 'VN', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Virgin Islands (UK)', 'code' => 'VGB', 'abbreviation' => 'VG', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Virgin Islands (US)', 'code' => 'VIR', 'abbreviation' => 'VI', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Wallis and Futuna', 'code' => 'WLF', 'abbreviation' => 'WF', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Western Sahara', 'code' => 'ESH', 'abbreviation' => 'EH', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Yemen', 'code' => 'YEM', 'abbreviation' => 'YE', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Zambia', 'code' => 'ZMB', 'abbreviation' => 'ZM', 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Zimbabwe', 'code' => 'ZWE', 'abbreviation' => 'ZW', 'created_at' => $now, 'updated_at' => $now],
    ];
}

/**
 * Returns an array of states
 *
 * This is used to pre-seed the database with production data.
 *
 * @return array
 */
function stateList()
{
    $usa_id = App\Http\Models\API\Country::where('code', 'USA')->first()->id;
    $can_id = App\Http\Models\API\Country::where('code', 'CAN')->first()->id;
    $now = Carbon\Carbon::now('utc')->toDateTimeString();

    return [
        ['label' => 'Alabama', 'code' => 'AL', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Alaska', 'code' => 'AK', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Arizona', 'code' => 'AZ', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Arkansas', 'code' => 'AR', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'California', 'code' => 'CA', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Colorado', 'code' => 'CO', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Connecticut', 'code' => 'CT', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Delaware', 'code' => 'DE', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'District of Columbia', 'code' => 'DC', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Florida', 'code' => 'FL', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Georgia', 'code' => 'GA', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Hawaii', 'code' => 'HI', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Idaho', 'code' => 'ID', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Illinois', 'code' => 'IL', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Indiana', 'code' => 'IN', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Iowa', 'code' => 'IA', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Kansas', 'code' => 'KS', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Kentucky', 'code' => 'KY', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Louisiana', 'code' => 'LA', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Maine', 'code' => 'ME', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Maryland', 'code' => 'MD', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Massachusetts', 'code' => 'MA', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Michigan', 'code' => 'MI', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Minnesota', 'code' => 'MN', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Mississippi', 'code' => 'MS', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Missouri', 'code' => 'MO', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Montana', 'code' => 'MT', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Nebraska', 'code' => 'NE', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Nevada', 'code' => 'NV', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'New Hampshire', 'code' => 'NH', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'New Jersey', 'code' => 'NJ', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'New Mexico', 'code' => 'NM', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'New York', 'code' => 'NY', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'North Carolina', 'code' => 'NC', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'North Dakota', 'code' => 'ND', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Ohio', 'code' => 'OH', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Oklahoma', 'code' => 'OK', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Oregon', 'code' => 'OR', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Pennsylvania', 'code' => 'PA', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Puerto Rico', 'code' => 'PR', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Rhode Island', 'code' => 'RI', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'South Carolina', 'code' => 'SC', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'South Dakota', 'code' => 'SD', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Tennessee', 'code' => 'TN', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Texas', 'code' => 'TX', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Utah', 'code' => 'UT', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Vermont', 'code' => 'VT', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Virginia', 'code' => 'VA', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Washington', 'code' => 'WA', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'West Virginia', 'code' => 'WV', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Wisconsin', 'code' => 'WI', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Wyoming', 'code' => 'WY', 'country_id' => $usa_id, 'created_at' => $now, 'updated_at' => $now],
        // Canada
        ['label' => 'Alberta', 'code' => 'AB', 'country_id' => $can_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'British Columbia', 'code' => 'BC', 'country_id' => $can_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Manitoba', 'code' => 'MB', 'country_id' => $can_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'New Brunswick', 'code' => 'NB', 'country_id' => $can_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Newfoundland', 'code' => 'NF', 'country_id' => $can_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Northwest Territories', 'code' => 'NT', 'country_id' => $can_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Nova Scotia', 'code' => 'NS', 'country_id' => $can_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Nunavut', 'code' => 'NU', 'country_id' => $can_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Ontario', 'code' => 'ON', 'country_id' => $can_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Prince Edward Island', 'code' => 'PE', 'country_id' => $can_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Quebec', 'code' => 'PQ', 'country_id' => $can_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Saskatchewan', 'code' => 'SK', 'country_id' => $can_id, 'created_at' => $now, 'updated_at' => $now],
        ['label' => 'Yukon', 'code' => 'YT', 'country_id' => $can_id, 'created_at' => $now, 'updated_at' => $now],
    ];
}

/**
 * Returns an array of Mobile Carriers
 *
 * @return array
 */
function mobileCarrierList()
{
    $now = Carbon\Carbon::now('utc')->toDateTimeString();
    $usa_id = App\Http\Models\API\Country::where('code', 'USA')->first()->id;
    $can_id = App\Http\Models\API\Country::where('code', 'CAN')->first()->id;

    return [
        ['country_id' => $usa_id, 'label' => 'AT&T', 'code' => 'att', 'created_at' => $now, 'updated_at' => $now],
        ['country_id' => $usa_id, 'label' => 'Air Fire Mobile', 'code' => 'airfiremobile', 'created_at' => $now, 'updated_at' => $now],
        ['country_id' => $usa_id, 'label' => 'Alaska Communicates', 'code' => 'alaskacommunicates', 'created_at' => $now, 'updated_at' => $now],
        ['country_id' => $usa_id, 'label' => 'Ameritech', 'code' => 'ameritech', 'created_at' => $now, 'updated_at' => $now],
        ['country_id' => $usa_id, 'label' => 'Boost Mobile', 'code' => 'moostmobile', 'created_at' => $now, 'updated_at' => $now],
        ['country_id' => $usa_id, 'label' => 'Clear Talk', 'code' => 'cleartalk', 'created_at' => $now, 'updated_at' => $now],
        ['country_id' => $usa_id, 'label' => 'Cricket', 'code' => 'cricket', 'created_at' => $now, 'updated_at' => $now],
        ['country_id' => $usa_id, 'label' => 'Metro PCS', 'code' => 'metropcs', 'created_at' => $now, 'updated_at' => $now],
        ['country_id' => $usa_id, 'label' => 'NexTech', 'code' => 'nextech', 'created_at' => $now, 'updated_at' => $now],
        ['country_id' => $usa_id, 'label' => 'Project Fi', 'code' => 'projectfi', 'created_at' => $now, 'updated_at' => $now],
        ['country_id' => $can_id, 'label' => 'Rogers Wireless', 'code' => 'rogerswireless', 'created_at' => $now, 'updated_at' => $now],
        ['country_id' => $usa_id, 'label' => 'Unicel', 'code' => 'unicel', 'created_at' => $now, 'updated_at' => $now],
        ['country_id' => $usa_id, 'label' => 'Verizon Wireless', 'code' => 'verizonwireless', 'created_at' => $now, 'updated_at' => $now],
        ['country_id' => $usa_id, 'label' => 'Virgin Mobile', 'code' => 'virginmobile', 'created_at' => $now, 'updated_at' => $now],
        ['country_id' => $usa_id, 'label' => 'Sprint', 'code' => 'sprint', 'created_at' => $now, 'updated_at' => $now],
        ['country_id' => $usa_id, 'label' => 'T-Mobile', 'code' => 'tmobile', 'created_at' => $now, 'updated_at' => $now],
    ];
}

/**
 * Returns an array of Duties
 *
 * This is used to pre-seed the database with production data.
 *
 * @return array
 */
function defaultDuties()
{
    $now = Carbon\Carbon::now('utc')->toDateTimeString();

    return [
        ['code' => 'DEFAULT', 'label' => 'Default', 'created_at' => $now, 'updated_at' => $now],
        ['code' => 'STUDENT', 'label' => 'Student', 'created_at' => $now, 'updated_at' => $now],
        ['code' => 'EMPLOYEE', 'label' => 'Employee', 'created_at' => $now, 'updated_at' => $now],
        ['code' => 'FACULTY', 'label' => 'Faculty', 'created_at' => $now, 'updated_at' => $now],
        ['code' => 'ADJUNCT', 'label' => 'Adjunct', 'created_at' => $now, 'updated_at' => $now],
        ['code' => 'ALUMNI', 'label' => 'Alumni', 'created_at' => $now, 'updated_at' => $now],
        ['code' => 'GUEST', 'label' => 'Guest', 'created_at' => $now, 'updated_at' => $now],
        ['code' => 'TENANT', 'label' => 'Tenant', 'created_at' => $now, 'updated_at' => $now],
        ['code' => 'CONTRACTOR', 'label' => 'Contractor', 'created_at' => $now, 'updated_at' => $now]
    ];
}

/**
 * Returns an array of Load Statuses
 *
 * This is used to pre-seed the database with production data.
 *
 * @return array
 */
function defaultLoadStatuses()
{
    $now = Carbon\Carbon::now('utc')->toDateTimeString();
    return [
        ['code' => 'FULL_TIME', 'label' => 'Full Time', 'created_at' => $now, 'updated_at' => $now],
        ['code' => 'PART_TIME', 'label' => 'Part Time', 'created_at' => $now, 'updated_at' => $now]
    ];
}

/**
 * Array of building name endings
 *
 * Returns an array of building name postfixes used for generating dummy data.
 *
 * @return array
 */
function buildingPostfixes()
{
    return [
        'Center',
        'Hall',
        'House',
        'Building',
        'Court',
        'Annex',
        'Pavilion',
    ];
}

/**
 * Array of geographic directions.
 *
 * Used for generating dummy data.
 *
 * @return array
 */
function directions()
{
    return [
        'North',
        'South',
        'East',
        'West',
    ];
}

/**
 * Array of floor names
 *
 * Used for generating dummy data.
 *
 * @return array
 */
function roomFloorLabels()
{
    return [
        'First Floor',
        'Second Floor',
        'Third Floor',
        'Fourth Floor'
    ];
}

/**
 * Dummy account data
 *
 * @return array
 */
function lukeSkywalkerAccount()
{
    return [
        'identifier' => '9999999',
        'name_prefix' => 'Mr.',
        'name_first' => 'Luke',
        'name_middle' => 'Cliegg',
        'name_last' => 'Skywalker',
        'name_postfix' => 'Jedi',
        'name_phonetic' => 'Luke Skywalker',
        'username' => 'skwall',
        'primary_duty_id' => \App\Http\Models\API\Duty::firstOrFail()->id,
    ];
}

/**
 * Dummy alias account data
 *
 * @return array
 */
function larsDunestriderAlias()
{
    return [
        'username' => 'dunesl',
        'account_id' => \App\Http\Models\API\Account::where('identifier', '9999999')->firstOrFail()->id,
    ];
}

/**
 * Dummy service account data
 *
 * @return array
 */
function deathStartServiceAccount()
{
    return [
        'username' => 'death_star',
        'name_first' => 'Death',
        'name_last' => 'Star',
        'identifier' => '9999998',
        'account_id' => \App\Http\Models\API\Account::where('identifier', '9999999')->firstOrFail()->id,
    ];
}

/**
 * Dummy Duty data
 *
 * @return array
 */
function jediMasterDuty()
{
    return [
        'code' => 'JEDI',
        'label' => 'Jedi Master'
    ];
}

function jediMasterMobileCarrier()
{
    $countryid = \App\Http\Models\API\Country::where('code', 'USA')->first()->id;

    return [
        'code' => 'FORCE',
        'country_id' => $countryid,
        'label' => 'The Force Wireless'
    ];
}

/**
 * Dummy email data
 *
 * @return array
 */
function jediMasterEmail($username = false, $identifier = false)
{
    $array = [
        'address' => 'JediMaster@gmail.com',
        'verified' => false,
        'verification_token' => generateVerificationToken()
    ];

    if ($username) {
        $array['username'] = 'skwall';
    } elseif ($identifier) {
        $array['identifier'] = '9999999';
    } else {
        $array['account_id'] = \App\Http\Models\API\Account::where('identifier', '9999999')->where('username', 'skwall')->firstOrFail()->id;
    }

    return $array;
}

/*
 * Dummy Mobile Phone data
 *
 * @return array
 */
function jediMasterMobilePhone($useCode = false, $username = false, $identifier = false)
{
    $array = [
        'number' => '9998887777',
        'verified' => false
    ];

    if ($useCode) {
        $array['mobile_carrier_code'] = 'FORCE';
    } else {
        $array['mobile_carrier_id'] = \App\Http\Models\API\MobileCarrier::where('code', 'FORCE')->firstOrFail()->id;
    }

    if ($username) {
        $array['username'] = 'skwall';
    } elseif ($identifier) {
        $array['identifier'] = '9999999';
    } else {
        $array['account_id'] = \App\Http\Models\API\Account::where('identifier', '9999999')->where('username', 'skwall')->firstOrFail()->id;
    }

    return $array;
}

/**
 * Dummy Department
 *
 * @return array
 */
function jediMasterDepartment()
{
    return [
        'academic' => true,
        'code' => 'jediKnights',
        'label' => 'Jedi Knights'
    ];
}

/**
 * Dummy Course
 *
 * @return array
 */
function jediMasterCourse()
{
    return [
        'code' => 'FORCE500',
        'label' => 'The Force',
        'department_id' => \App\Http\Models\API\Department::where('code', 'jediKnights')->firstOrFail()->id,
        'course_level' => 500
    ];
}

/**
 * Dummy School
 *
 * @return array
 */
function jediMasterSchool()
{
    return [
        'code' => 'Jedi_Academy',
        'label' => 'Jedi Academy'
    ];
}

/**
 * Dummy Room
 *
 * @return array
 */
function jediMasterRoom()
{
    return [
        'code' => 'AROOMFORAJEDI',
        'building_id' => \App\Http\Models\API\Building::get()->random()->id,
        'floor_number' => 3,
        'floor_label' => 'Third Floor',
        'room_number' => 306,
        'room_label' => 'Jedi Dorm 306'
    ];

}

/**
 * Dummy Address
 *
 * @return array
 */
function jediMasterAddress()
{
    $state = \App\Http\Models\API\State::where('code', 'NY')->firstOrFail();
    return [
        'account_id' => \App\Http\Models\API\Account::where('identifier', '9999999')->where('username', 'skwall')->firstOrFail()->id,
        'addressee' => \App\Http\Models\API\Account::where('identifier', '9999999')->where('username', 'skwall')->firstOrFail()->format_full_name(true),
        'organization' => 'Jedi Academy',
        'line_1' => '309 X-Wing Drive',
        'line_2' => 'Earth',
        'city' => 'Troy',
        'state_id' => $state->id,
        'zip' => 12180,
        'country_id' => $state->country->id
    ];
}

/**
 * Returns the LDAP configuration
 *
 * @return array
 */
function ldap_config()
{
    return [
        'enabled' => Settings::get('ldap-enabled', false),
        'hosts' => Settings::get('ldap-hosts', []),
        'bind_user' => Settings::get('ldap-bind-user', ''),
        'bind_password' => Settings::get('ldap-bind-password', ''),
        'tree_base' => Settings::get('ldap-tree-base', ''),
        'base_user_ou_dn' => Settings::get('ldap-base-user-dn', ''),
        'base_group_ou_dn' => Settings::get('ldap-base-group-dn', ''),
        'delete_users' => Settings::get('ldap-delete-users', false),
        'duties_map_to_ou' => Settings::get('ldap-duties-map-to-ou', true),
        'home_drive_letter' => Settings::get('ldap-home-drive-letter', ''),
        'home_drive_path_pattern' => Settings::get('ldap-home-drive-path-pattern', ''),
        'email_domain' => Settings::get('ldap-email-domain', ''),
        'use_trash_ou' => Settings::get('ldap-use-trash-ou', true)
    ];
}

/**
 * Generates random bytes and base 64 encodes them
 *
 * @param int $length
 * @return string
 */
function generate_random_encoded_bytes($length = 32)
{
    if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
        // We are on php 7 or higher so we can use new more secure function
        return base64_encode(random_bytes($length));
    } else {
        // We are not on php 7 and have to fall back on openssl_random_pseudo_bytes
        return base64_encode(openssl_random_pseudo_bytes($length));
    }
}


/**
 * Generates an IV for encryption
 *
 * @return string
 */
function generate_iv()
{
    return generate_random_encoded_bytes(openssl_cipher_iv_length('aes-256-cbc'));
}

/**
 * Encrypts broadcast data string
 *
 * Returns a string with the IV appended to encrypted data deliminated by a colon
 *
 * @param $data
 * @param null $key
 * @param null $iv
 * @return string
 */
function encrypt_broadcast_data($data, $key = null, $iv = null)
{
    $key = (empty($key)) ? base64_decode(env('BC_KEY')) : base64_decode($key);
    $iv = (empty($iv)) ? base64_decode(generate_iv()) : base64_decode($iv);

    if (empty($key))
        throw new \Illuminate\Contracts\Encryption\EncryptException('No BC_KEY defined. Please generate a BC_KEY and ensure it is in your .env file. Hint: `php artisan orm:bckey`');

    $encrypted_data = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
    return $encrypted_data . ':' . base64_encode($iv);
}

/**
 * @param string $path
 * @return mixed|string
 */
function fixPath($path = '')
{
    $path = str_replace('\\', '/', trim($path));
    return (substr($path, -1) != '/') ? $path .= '/' : $path;
}

/**
 * @param string $message
 * @param string $number
 * @param string $carrier_code
 */
function sendSMS($message, $number, $carrier_code)
{
    // Queue the message for delivery
    SMS::queue($message, [], function ($sms) use ($number, $carrier_code) {
        if (env('SMS_DRIVER', 'email') === 'email') {
            $sms->to('+1' . $number, $carrier_code);
        } else {
            $sms->to('+1' . $number);
        }
    });
}

/**
 * @param array $html_parts
 * @param string $subject
 * @param string $full_name
 * @param string $from_address
 * @param string $to_address
 * @param string $template
 */
function sendEmail($html_parts = [], $subject, $full_name, $from_address, $to_address, $template = 'emails.alert')
{
    // Build the array of html parts to be used in the template
    $html_parts['subject'] = $subject;
    // Build the mail class
    $beautymail = app()->make(Beautymail::class);
    // Queue the message for delivery
    $beautymail->queue($template, $html_parts,
        function ($message) use ($full_name, $from_address, $to_address, $subject) {
            $message
                ->from($from_address)
                ->to($to_address, $full_name)
                ->subject($subject);
        });
}

/**
 * @param bool $ofProxy
 * @return string
 */
function getRequestIP($ofProxy = false)
{
    $ip = \Request::ip();

    if (!$ofProxy) {
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        }
    } else {
        if (empty($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = null;
        }
    }

    return $ip;
}

function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
{
    $sets = array();
    if (strpos($available_sets, 'l') !== false)
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
    if (strpos($available_sets, 'u') !== false)
        $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
    if (strpos($available_sets, 'd') !== false)
        $sets[] = '0123456789';
    if (strpos($available_sets, 's') !== false)
        $sets[] = '!@#$%&*?';

    $all = '';
    $password = '';
    foreach ($sets as $set) {
        $password .= $set[array_rand(str_split($set))];
        $all .= $set;
    }

    $all = str_split($all);
    for ($i = 0; $i < $length - count($sets); $i++)
        $password .= $all[array_rand($all)];

    $password = str_shuffle($password);

    if (!$add_dashes)
        return $password;

    $dash_len = floor(sqrt($length));
    $dash_str = '';
    while (strlen($password) > $dash_len) {
        $dash_str .= substr($password, 0, $dash_len) . '-';
        $password = substr($password, $dash_len);
    }
    $dash_str .= $password;
    return $dash_str;
}