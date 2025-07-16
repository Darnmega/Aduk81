<?php 
// base files
$base_url = '/Aduk8/system/';
$base_dir_assets = $base_url.'assets/';
$base_dir_uploads = $base_url.'accounts/uploads/';
$base_dir_learning_resources = $base_url.'accounts/uploads/files/learning-resources';
$base_dir_userfile_uploads = $base_url.'accounts/uploads/files/usr-docs/';
// 

// path files
$path_to_user_domain = $base_url.'accounts/school-account/';
$profile_pic_url = $base_dir_uploads.'img/usr-pics/';

$postDataFile = $path_to_user_domain.'includes/postData.php';
$getDataFile = $path_to_user_domain.'includes/getData.php';


// Dashboard
$dashboardFile = $path_to_user_domain.'dashboard.php';

//Registration
$registerStudentFile = $path_to_user_domain.'registration/student.php';
$registerStaffFile = $path_to_user_domain.'registration/staff.php';
$registerGuardiansFile = $path_to_user_domain.'registration/guardians.php';
$registerSubjectFile = $path_to_user_domain.'registration/subjects.php';
$registerClassRangeFile = $path_to_user_domain.'registration/class-range.php';
$registerHouseFile = $path_to_user_domain.'registration/house.php';
$registerGradeLevelFile = $path_to_user_domain.'registration/grade-levels.php';
$registerBlockFile = $path_to_user_domain.'registration/blocks.php';
$registerClassFile = $path_to_user_domain.'registration/classes.php';

//Accounts
$accountStaffListFile = $path_to_user_domain.'accounts/staff-list.php';
$accountStudentListFile = $path_to_user_domain.'accounts/staff-list.php';
$accountGuardianlistFile = $path_to_user_domain.'accounts/staff-list.php';
$accountSubjectListFile = $path_to_user_domain.'accounts/staff-list.php';
$accountHouseListFile = $path_to_user_domain.'accounts/staff-list.php';
$accountClassListFile = $path_to_user_domain.'accounts/staff-list.php';
$accountSchoolBlocksListFile = $path_to_user_domain.'accounts/staff-list.php';
$accountStaffListFile = $path_to_user_domain.'accounts/staff-list.php';
$accountStaffListFile = $path_to_user_domain.'accounts/staff-list.php';

//Class Allocation
$pathToClassAllocationFolder = $path_to_user_domain.'class-allocation/';
$studentsAllocationFile = $pathToClassAllocationFolder.'students.php';
$teacherAllocationFile = $pathToClassAllocationFolder.'teacher.php';

// Learning Resources
$new_learning_resources_url = $path_to_user_domain.'learning-resources/upload.php';

//profile
$userProfileFile = $path_to_user_domain."settings/profile.php";
// Sign Out
$logoutUrlFile = $base_url."includes/sign-out.php";

$signInFile = '/Aduk8/sign-in.php';

//globally used arrays
$maritalStatusOptions = array(
    'Single', 'Married', 'Divorced', 'Widowed'
);
$genderOptions = array(
    "Male",
    "Female",
    "Rather Not Say",
    "Others"
) ;

$schoolClassificationOptions = array(
    "Primary School",
    "Junior Secondary School",
    "Senior Secondary School",
    "International School"
);

$staffOccupationOptions = array(
    "Principal",
    "Vice Principal",
    "Head Of House",
    "Teacher"
);
$staffOccupationPositionOptions = array(
    "Not Applicable",
    "Junior",
    "senior"
);

$nationalityOptions = array(
    "AF" => "Afghanistan",
    "AL" => "Albania",
    "DZ" => "Algeria",
    "US" => "United States",
    "AD" => "Andorra",
    "AO" => "Angola",
    "AG" => "Antigua and Barbuda",
    "AR" => "Argentina",
    "AM" => "Armenia",
    "AU" => "Australia",
    "AT" => "Austria",
    "AZ" => "Azerbaijan",
    "BS" => "Bahamas",
    "BH" => "Bahrain",
    "BD" => "Bangladesh",
    "BB" => "Barbados",
    "BY" => "Belarus",
    "BE" => "Belgium",
    "BZ" => "Belize",
    "BJ" => "Benin",
    "BT" => "Bhutan",
    "BO" => "Bolivia",
    "BA" => "Bosnia and Herzegovina",
    "BW" => "Botswana",
    "BR" => "Brazil",
    "BN" => "Brunei",
    "BG" => "Bulgaria",
    "BF" => "Burkina Faso",
    "BI" => "Burundi",
    "KH" => "Cambodia",
    "CM" => "Cameroon",
    "CA" => "Canada",
    "CV" => "Cape Verde",
    "CF" => "Central African Republic",
    "TD" => "Chad",
    "CL" => "Chile",
    "CN" => "China",
    "CO" => "Colombia",
    "KM" => "Comoros",
    "CD" => "Congo (Democratic Republic)",
    "CG" => "Congo (Republic)",
    "CR" => "Costa Rica",
    "HR" => "Croatia",
    "CU" => "Cuba",
    "CY" => "Cyprus",
    "CZ" => "Czech Republic",
    "DK" => "Denmark",
    "DJ" => "Djibouti",
    "DM" => "Dominica",
    "DO" => "Dominican Republic",
    "EC" => "Ecuador",
    "EG" => "Egypt",
    "SV" => "El Salvador",
    "GQ" => "Equatorial Guinea",
    "ER" => "Eritrea",
    "EE" => "Estonia",
    "ET" => "Ethiopia",
    "FJ" => "Fiji",
    "FI" => "Finland",
    "FR" => "France",
    "GA" => "Gabon",
    "GM" => "Gambia",
    "GE" => "Georgia",
    "DE" => "Germany",
    "GH" => "Ghana",
    "GR" => "Greece",
    "GD" => "Grenada",
    "GT" => "Guatemala",
    "GN" => "Guinea",
    "GW" => "Guinea-Bissau",
    "GY" => "Guyana",
    "HT" => "Haiti",
    "HN" => "Honduras",
    "HU" => "Hungary",
    "IS" => "Iceland",
    "IN" => "India",
    "ID" => "Indonesia",
    "IR" => "Iran",
    "IQ" => "Iraq",
    "IE" => "Ireland",
    "IL" => "Israel",
    "IT" => "Italy",
    "JM" => "Jamaica",
    "JP" => "Japan",
    "JO" => "Jordan",
    "KZ" => "Kazakhstan",
    "KE" => "Kenya",
    "KI" => "Kiribati",
    "KP" => "North Korea",
    "KR" => "South Korea",
    "KW" => "Kuwait",
    "KG" => "Kyrgyzstan",
    "LA" => "Laos",
    "LV" => "Latvia",
    "LB" => "Lebanon",
    "LS" => "Lesotho",
    "LR" => "Liberia",
    "LY" => "Libya",
    "LI" => "Liechtenstein",
    "LT" => "Lithuania",
    "LU" => "Luxembourg",
    "MK" => "North Macedonia",
    "MG" => "Madagascar",
    "MW" => "Malawi",
    "MY" => "Malaysia",
    "MV" => "Maldives",
    "ML" => "Mali",
    "MT" => "Malta",
    "MH" => "Marshall Islands",
    "MR" => "Mauritania",
    "MU" => "Mauritius",
    "MX" => "Mexico",
    "FM" => "Micronesia",
    "MD" => "Moldova",
    "MC" => "Monaco",
    "MN" => "Mongolia",
    "ME" => "Montenegro",
    "MA" => "Morocco",
    "MZ" => "Mozambique",
    "MM" => "Myanmar",
    "NA" => "Namibia",
    "NR" => "Nauru",
    "NP" => "Nepal",
    "NL" => "Netherlands",
    "NZ" => "New Zealand",
    "NI" => "Nicaragua",
    "NE" => "Niger",
    "NG" => "Nigeria",
    "NO" => "Norway",
    "OM" => "Oman",
    "PK" => "Pakistan",
    "PW" => "Palau",
    "PA" => "Panama",
    "PG" => "Papua New Guinea",
    "PY" => "Paraguay",
    "PE" => "Peru",
    "PH" => "Philippines",
    "PL" => "Poland",
    "PT" => "Portugal",
    "QA" => "Qatar",
    "RO" => "Romania",
    "RU" => "Russia",
    "RW" => "Rwanda",
    "KN" => "Saint Kitts and Nevis",
    "LC" => "Saint Lucia",
    "VC" => "Saint Vincent and the Grenadines",
    "WS" => "Samoa",
    "SM" => "San Marino",
    "ST" => "Sao Tome and Principe",
    "SA" => "Saudi Arabia",
    "SN" => "Senegal",
    "RS" => "Serbia",
    "SC" => "Seychelles",
    "SL" => "Sierra Leone",
    "SG" => "Singapore",
    "SK" => "Slovakia",
    "SI" => "Slovenia",
    "SB" => "Solomon Islands",
    "SO" => "Somalia",
    "ZA" => "South Africa",
    "SS" => "South Sudan",
    "ES" => "Spain",
    "LK" => "Sri Lanka",
    "SD" => "Sudan",
    "SR" => "Suriname",
    "SE" => "Sweden",
    "CH" => "Switzerland",
    "SY" => "Syria",
    "TW" => "Taiwan",
    "TJ" => "Tajikistan",
    "TZ" => "Tanzania",
    "TH" => "Thailand",
    "TL" => "Timor-Leste",
    "TG" => "Togo",
    "TO" => "Tonga",
    "TT" => "Trinidad and Tobago",
    "TN" => "Tunisia",
    "TR" => "Turkey",
    "TM" => "Turkmenistan",
    "TV" => "Tuvalu",
    "UG" => "Uganda",
    "UA" => "Ukraine",
    "AE" => "United Arab Emirates",
    "GB" => "United Kingdom",
    "UY" => "Uruguay",
    "UZ" => "Uzbekistan",
    "VU" => "Vanuatu",
    "VA" => "Vatican City",
    "VE" => "Venezuela",
    "VN" => "Vietnam",
    "YE" => "Yemen",
    "ZM" => "Zambia",
    "ZW" => "Zimbabwe"
);

$languageOptions = array(
    "Afrikaans" => "Afrikaans",
    "Albanian" => "Albanian",
    "Amharic" => "Amharic",
    "Arabic" => "Arabic",
    "Armenian" => "Armenian",
    "Azerbaijani" => "Azerbaijani",
    "Basque" => "Basque",
    "Belarusian" => "Belarusian",
    "Bengali" => "Bengali",
    "Bosnian" => "Bosnian",
    "Bulgarian" => "Bulgarian",
    "Burmese" => "Burmese",
    "Catalan" => "Catalan",
    "Cebuano" => "Cebuano",
    "Chinese (Simplified)" => "Chinese (Simplified)",
    "Chinese (Traditional)" => "Chinese (Traditional)",
    "Corsican" => "Corsican",
    "Croatian" => "Croatian",
    "Czech" => "Czech",
    "Danish" => "Danish",
    "Dutch" => "Dutch",
    "English" => "English",
    "Esperanto" => "Esperanto",
    "Estonian" => "Estonian",
    "Filipino" => "Filipino",
    "Finnish" => "Finnish",
    "French" => "French",
    "Frisian" => "Frisian",
    "Galician" => "Galician",
    "Georgian" => "Georgian",
    "German" => "German",
    "Greek" => "Greek",
    "Gujarati" => "Gujarati",
    "Haitian Creole" => "Haitian Creole",
    "Hausa" => "Hausa",
    "Hawaiian" => "Hawaiian",
    "Hebrew" => "Hebrew",
    "Hindi" => "Hindi",
    "Hmong" => "Hmong",
    "Hungarian" => "Hungarian",
    "Icelandic" => "Icelandic",
    "Igbo" => "Igbo",
    "Indonesian" => "Indonesian",
    "Irish" => "Irish",
    "Italian" => "Italian",
    "Japanese" => "Japanese",
    "Javanese" => "Javanese",
    "Kannada" => "Kannada",
    "Kazakh" => "Kazakh",
    "Khmer" => "Khmer",
    "Kinyarwanda" => "Kinyarwanda",
    "Korean" => "Korean",
    "Kurdish" => "Kurdish",
    "Kyrgyz" => "Kyrgyz",
    "Lao" => "Lao",
    "Latin" => "Latin",
    "Latvian" => "Latvian",
    "Lithuanian" => "Lithuanian",
    "Luxembourgish" => "Luxembourgish",
    "Macedonian" => "Macedonian",
    "Malagasy" => "Malagasy",
    "Malay" => "Malay",
    "Malayalam" => "Malayalam",
    "Maltese" => "Maltese",
    "Maori" => "Maori",
    "Marathi" => "Marathi",
    "Mongolian" => "Mongolian",
    "Nepali" => "Nepali",
    "Norwegian" => "Norwegian",
    "Nyanja (Chichewa)" => "Nyanja (Chichewa)",
    "Odia (Oriya)" => "Odia (Oriya)",
    "Pashto" => "Pashto",
    "Persian" => "Persian",
    "Polish" => "Polish",
    "Portuguese" => "Portuguese",
    "Punjabi" => "Punjabi",
    "Romanian" => "Romanian",
    "Russian" => "Russian",
    "Samoan" => "Samoan",
    "Scots Gaelic" => "Scots Gaelic",
    "Serbian" => "Serbian",
    "Sesotho" => "Sesotho",
    "Shona" => "Shona",
    "Sindhi" => "Sindhi",
    "Sinhala" => "Sinhala",
    "Slovak" => "Slovak",
    "Slovenian" => "Slovenian",
    "Somali" => "Somali",
    "Spanish" => "Spanish",
    "Sundanese" => "Sundanese",
    "Swahili" => "Swahili",
    "Swedish" => "Swedish",
    "Tagalog" => "Tagalog",
    "Tajik" => "Tajik",
    "Tamil" => "Tamil",
    "Tatar" => "Tatar",
    "Telugu" => "Telugu",
    "Thai" => "Thai",
    "Turkish" => "Turkish",
    "Turkmen" => "Turkmen",
    "Ukrainian" => "Ukrainian",
    "Urdu" => "Urdu",
    "Uyghur" => "Uyghur",
    "Uzbek" => "Uzbek",
    "Vietnamese" => "Vietnamese",
    "Welsh" => "Welsh",
    "Xhosa" => "Xhosa",
    "Yiddish" => "Yiddish",
    "Yoruba" => "Yoruba",
    "Zulu" => "Zulu"
);
$nationalities = array(
    "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", 
    "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", 
    "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", 
    "Belarus", "Belgium", "Belize", "Benin", "Bhutan", 
    "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", 
    "Bulgaria", "Burkina Faso", "Burundi", "Côte d'Ivoire", "Cabo Verde", 
    "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", 
    "Chile", "China", "Colombia", "Comoros", "Congo (Congo-Brazzaville)", 
    "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czechia (Czech Republic)", 
    "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", 
    "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", 
    "Eswatini (fmr. Swaziland)", "Ethiopia", "Fiji", "Finland", "France", 
    "Gabon", "Gambia", "Georgia", "Germany", "Ghana", 
    "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", 
    "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", 
    "India", "Indonesia", "Iran", "Iraq", "Ireland", 
    "Israel", "Italy", "Jamaica", "Japan", "Jordan", 
    "Kazakhstan", "Kenya", "Kiribati", "Kuwait", "Kyrgyzstan", 
    "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", 
    "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Madagascar", 
    "Malawi", "Malaysia", "Maldives", "Mali", "Malta", 
    "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", 
    "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", 
    "Mozambique", "Myanmar (formerly Burma)", "Namibia", "Nauru", "Nepal", 
    "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", 
    "North Korea", "North Macedonia", "Norway", "Oman", "Pakistan", 
    "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", 
    "Philippines", "Poland", "Portugal", "Qatar", "Romania", 
    "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", 
    "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", 
    "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", 
    "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", 
    "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", 
    "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", 
    "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", 
    "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", 
    "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States of America", 
    "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", 
    "Vietnam", "Yemen", "Zambia", "Zimbabwe"
);

$genders = array(
    'male' , 'female', 'others'
);
$current_datetime = date('Y-m-d H:i:s');

$intStatuses = array(
    0 => "Inactive",
    1 => "Active",
    2 => "Pending",
    3 => "Suspended",
    4 => "Deleted"
);
$studentIntStatus = array(
    0 => 'Pending', 
    1 => 'Active', 
    2 => 'Suspended', 
    3 => 'Expelled', 
    4 => 'Graduate'
);
$employmentStatus = array(
    0 => 'Pending', 
    1 => 'Active', 
    2 => 'Suspended', 
    3 => 'Resigned', 
    4 => 'Retired', 
    5 => 'Terminated'
);
