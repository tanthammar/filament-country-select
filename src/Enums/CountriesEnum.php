<?php

namespace TantHammar\FilamentCountrySelect\Enums;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

/** ISO 3166-1 alpha-2, plus XK for Kosovo, which has no ISO code of its own. */
enum CountriesEnum: string
{
    case AF = 'AF';
    case AL = 'AL';
    case DZ = 'DZ';
    case AS = 'AS';
    case AD = 'AD';
    case AO = 'AO';
    case AI = 'AI';
    case AQ = 'AQ';
    case AG = 'AG';
    case AR = 'AR';
    case AM = 'AM';
    case AW = 'AW';
    case AU = 'AU';
    case AT = 'AT';
    case AZ = 'AZ';
    case BS = 'BS';
    case BH = 'BH';
    case BD = 'BD';
    case BB = 'BB';
    case BY = 'BY';
    case BE = 'BE';
    case BZ = 'BZ';
    case BJ = 'BJ';
    case BM = 'BM';
    case BT = 'BT';
    case BO = 'BO';
    case BQ = 'BQ';
    case BA = 'BA';
    case BW = 'BW';
    case BR = 'BR';
    case IO = 'IO';
    case BN = 'BN';
    case BG = 'BG';
    case BF = 'BF';
    case BI = 'BI';
    case CV = 'CV';
    case KH = 'KH';
    case CM = 'CM';
    case CA = 'CA';
    case KY = 'KY';
    case CF = 'CF';
    case TD = 'TD';
    case CL = 'CL';
    case CN = 'CN';
    case CX = 'CX';
    case CC = 'CC';
    case CO = 'CO';
    case KM = 'KM';
    case CG = 'CG';
    case CD = 'CD';
    case CK = 'CK';
    case CR = 'CR';
    case HR = 'HR';
    case CU = 'CU';
    case CW = 'CW';
    case CY = 'CY';
    case CZ = 'CZ';
    case CI = 'CI';
    case DK = 'DK';
    case DJ = 'DJ';
    case DM = 'DM';
    case DO = 'DO';
    case EC = 'EC';
    case EG = 'EG';
    case SV = 'SV';
    case GQ = 'GQ';
    case ER = 'ER';
    case EE = 'EE';
    case SZ = 'SZ';
    case ET = 'ET';
    case FK = 'FK';
    case FO = 'FO';
    case FJ = 'FJ';
    case FI = 'FI';
    case FR = 'FR';
    case GF = 'GF';
    case PF = 'PF';
    case GA = 'GA';
    case GM = 'GM';
    case GE = 'GE';
    case DE = 'DE';
    case GH = 'GH';
    case GI = 'GI';
    case GR = 'GR';
    case GL = 'GL';
    case GD = 'GD';
    case GP = 'GP';
    case GU = 'GU';
    case GT = 'GT';
    case GG = 'GG';
    case GN = 'GN';
    case GW = 'GW';
    case GY = 'GY';
    case HT = 'HT';
    case VA = 'VA';
    case HN = 'HN';
    case HK = 'HK';
    case HU = 'HU';
    case IS = 'IS';
    case IN = 'IN';
    case ID = 'ID';
    case IR = 'IR';
    case IQ = 'IQ';
    case IE = 'IE';
    case IM = 'IM';
    case IL = 'IL';
    case IT = 'IT';
    case JM = 'JM';
    case JP = 'JP';
    case JE = 'JE';
    case JO = 'JO';
    case KZ = 'KZ';
    case KE = 'KE';
    case KI = 'KI';
    case KP = 'KP';
    case KR = 'KR';
    case XK = 'XK';
    case KW = 'KW';
    case KG = 'KG';
    case LA = 'LA';
    case LV = 'LV';
    case LB = 'LB';
    case LS = 'LS';
    case LR = 'LR';
    case LY = 'LY';
    case LI = 'LI';
    case LT = 'LT';
    case LU = 'LU';
    case MO = 'MO';
    case MG = 'MG';
    case MW = 'MW';
    case MY = 'MY';
    case MV = 'MV';
    case ML = 'ML';
    case MT = 'MT';
    case MH = 'MH';
    case MQ = 'MQ';
    case MR = 'MR';
    case MU = 'MU';
    case YT = 'YT';
    case MX = 'MX';
    case FM = 'FM';
    case MD = 'MD';
    case MC = 'MC';
    case MN = 'MN';
    case ME = 'ME';
    case MS = 'MS';
    case MA = 'MA';
    case MZ = 'MZ';
    case MM = 'MM';
    case NA = 'NA';
    case NR = 'NR';
    case NP = 'NP';
    case NL = 'NL';
    case NC = 'NC';
    case NZ = 'NZ';
    case NI = 'NI';
    case NE = 'NE';
    case NG = 'NG';
    case NU = 'NU';
    case NF = 'NF';
    case MK = 'MK';
    case MP = 'MP';
    case NO = 'NO';
    case OM = 'OM';
    case PK = 'PK';
    case PW = 'PW';
    case PS = 'PS';
    case PA = 'PA';
    case PG = 'PG';
    case PY = 'PY';
    case PE = 'PE';
    case PH = 'PH';
    case PN = 'PN';
    case PL = 'PL';
    case PT = 'PT';
    case PR = 'PR';
    case QA = 'QA';
    case RO = 'RO';
    case RU = 'RU';
    case RW = 'RW';
    case RE = 'RE';
    case BL = 'BL';
    case SH = 'SH';
    case KN = 'KN';
    case LC = 'LC';
    case MF = 'MF';
    case PM = 'PM';
    case VC = 'VC';
    case WS = 'WS';
    case SM = 'SM';
    case ST = 'ST';
    case SA = 'SA';
    case SN = 'SN';
    case RS = 'RS';
    case SC = 'SC';
    case SL = 'SL';
    case SG = 'SG';
    case SX = 'SX';
    case SK = 'SK';
    case SI = 'SI';
    case SB = 'SB';
    case SO = 'SO';
    case ZA = 'ZA';
    case GS = 'GS';
    case SS = 'SS';
    case ES = 'ES';
    case LK = 'LK';
    case SD = 'SD';
    case SR = 'SR';
    case SJ = 'SJ';
    case SE = 'SE';
    case CH = 'CH';
    case SY = 'SY';
    case TW = 'TW';
    case TJ = 'TJ';
    case TZ = 'TZ';
    case TH = 'TH';
    case TL = 'TL';
    case TG = 'TG';
    case TK = 'TK';
    case TO = 'TO';
    case TT = 'TT';
    case TN = 'TN';
    case TM = 'TM';
    case TC = 'TC';
    case TV = 'TV';
    case TR = 'TR';
    case UG = 'UG';
    case UA = 'UA';
    case AE = 'AE';
    case GB = 'GB';
    case US = 'US';
    case UY = 'UY';
    case UZ = 'UZ';
    case VU = 'VU';
    case VE = 'VE';
    case VN = 'VN';
    case VG = 'VG';
    case VI = 'VI';
    case WF = 'WF';
    case EH = 'EH';
    case YE = 'YE';
    case ZM = 'ZM';
    case ZW = 'ZW';
    case AX = 'AX';

    public function getLabel(): string
    {
        return $this->getName();
    }

    /** English is the last resort, whatever is configured. */
    public function getName(?string $locale = null): string
    {
        $key = 'filament-country-select::countries.'.$this->value;

        $locales = [$locale, config('filament-country-select.fallback-locale', 'en'), 'en'];

        foreach ($locales as $try) {
            $name = trans($key, [], $try);

            if (is_string($name) && $name !== $key) {
                return $name;
            }
        }

        return $this->value;
    }

    /** Not unique: the United States and Canada share +1. */
    public function getDialCode(): string
    {
        return match ($this) {
            self::AF => '+93',
            self::AL => '+355',
            self::DZ => '+213',
            self::AS => '+1-684',
            self::AD => '+376',
            self::AO => '+244',
            self::AI => '+1-264',
            self::AQ => '+672',
            self::AG => '+1-268',
            self::AR => '+54',
            self::AM => '+374',
            self::AW => '+297',
            self::AU => '+61',
            self::AT => '+43',
            self::AZ => '+994',
            self::BS => '+1-242',
            self::BH => '+973',
            self::BD => '+880',
            self::BB => '+1-246',
            self::BY => '+375',
            self::BE => '+32',
            self::BZ => '+501',
            self::BJ => '+229',
            self::BM => '+1-441',
            self::BT => '+975',
            self::BO => '+591',
            self::BQ => '+599',
            self::BA => '+387',
            self::BW => '+267',
            self::BR => '+55',
            self::IO => '+246',
            self::BN => '+673',
            self::BG => '+359',
            self::BF => '+226',
            self::BI => '+257',
            self::CV => '+238',
            self::KH => '+855',
            self::CM => '+237',
            self::CA => '+1',
            self::KY => '+1-345',
            self::CF => '+236',
            self::TD => '+235',
            self::CL => '+56',
            self::CN => '+86',
            self::CX => '+61',
            self::CC => '+61',
            self::CO => '+57',
            self::KM => '+269',
            self::CG => '+242',
            self::CD => '+243',
            self::CK => '+682',
            self::CR => '+506',
            self::HR => '+385',
            self::CU => '+53',
            self::CW => '+599',
            self::CY => '+357',
            self::CZ => '+420',
            self::CI => '+225',
            self::DK => '+45',
            self::DJ => '+253',
            self::DM => '+1-767',
            self::DO => '+1-809',
            self::EC => '+593',
            self::EG => '+20',
            self::SV => '+503',
            self::GQ => '+240',
            self::ER => '+291',
            self::EE => '+372',
            self::SZ => '+268',
            self::ET => '+251',
            self::FK => '+500',
            self::FO => '+298',
            self::FJ => '+679',
            self::FI => '+358',
            self::FR => '+33',
            self::GF => '+594',
            self::PF => '+689',
            self::GA => '+241',
            self::GM => '+220',
            self::GE => '+995',
            self::DE => '+49',
            self::GH => '+233',
            self::GI => '+350',
            self::GR => '+30',
            self::GL => '+299',
            self::GD => '+1-473',
            self::GP => '+590',
            self::GU => '+1-671',
            self::GT => '+502',
            self::GG => '+44-1481',
            self::GN => '+224',
            self::GW => '+245',
            self::GY => '+592',
            self::HT => '+509',
            self::VA => '+379',
            self::HN => '+504',
            self::HK => '+852',
            self::HU => '+36',
            self::IS => '+354',
            self::IN => '+91',
            self::ID => '+62',
            self::IR => '+98',
            self::IQ => '+964',
            self::IE => '+353',
            self::IM => '+44-1624',
            self::IL => '+972',
            self::IT => '+39',
            self::JM => '+1-876',
            self::JP => '+81',
            self::JE => '+44-1534',
            self::JO => '+962',
            self::KZ => '+7',
            self::KE => '+254',
            self::KI => '+686',
            self::KP => '+850',
            self::KR => '+82',
            self::XK => '+383',
            self::KW => '+965',
            self::KG => '+996',
            self::LA => '+856',
            self::LV => '+371',
            self::LB => '+961',
            self::LS => '+266',
            self::LR => '+231',
            self::LY => '+218',
            self::LI => '+423',
            self::LT => '+370',
            self::LU => '+352',
            self::MO => '+853',
            self::MG => '+261',
            self::MW => '+265',
            self::MY => '+60',
            self::MV => '+960',
            self::ML => '+223',
            self::MT => '+356',
            self::MH => '+692',
            self::MQ => '+596',
            self::MR => '+222',
            self::MU => '+230',
            self::YT => '+262',
            self::MX => '+52',
            self::FM => '+691',
            self::MD => '+373',
            self::MC => '+377',
            self::MN => '+976',
            self::ME => '+382',
            self::MS => '+1-664',
            self::MA => '+212',
            self::MZ => '+258',
            self::MM => '+95',
            self::NA => '+264',
            self::NR => '+674',
            self::NP => '+977',
            self::NL => '+31',
            self::NC => '+687',
            self::NZ => '+64',
            self::NI => '+505',
            self::NE => '+227',
            self::NG => '+234',
            self::NU => '+683',
            self::NF => '+672',
            self::MK => '+389',
            self::MP => '+1-670',
            self::NO => '+47',
            self::OM => '+968',
            self::PK => '+92',
            self::PW => '+680',
            self::PS => '+970',
            self::PA => '+507',
            self::PG => '+675',
            self::PY => '+595',
            self::PE => '+51',
            self::PH => '+63',
            self::PN => '+64',
            self::PL => '+48',
            self::PT => '+351',
            self::PR => '+1-787',
            self::QA => '+974',
            self::RO => '+40',
            self::RU => '+7',
            self::RW => '+250',
            self::RE => '+262',
            self::BL => '+590',
            self::SH => '+290',
            self::KN => '+1-869',
            self::LC => '+1-758',
            self::MF => '+590',
            self::PM => '+508',
            self::VC => '+1-784',
            self::WS => '+685',
            self::SM => '+378',
            self::ST => '+239',
            self::SA => '+966',
            self::SN => '+221',
            self::RS => '+381',
            self::SC => '+248',
            self::SL => '+232',
            self::SG => '+65',
            self::SX => '+1-721',
            self::SK => '+421',
            self::SI => '+386',
            self::SB => '+677',
            self::SO => '+252',
            self::ZA => '+27',
            self::GS => '+500',
            self::SS => '+211',
            self::ES => '+34',
            self::LK => '+94',
            self::SD => '+249',
            self::SR => '+597',
            self::SJ => '+47',
            self::SE => '+46',
            self::CH => '+41',
            self::SY => '+963',
            self::TW => '+886',
            self::TJ => '+992',
            self::TZ => '+255',
            self::TH => '+66',
            self::TL => '+670',
            self::TG => '+228',
            self::TK => '+690',
            self::TO => '+676',
            self::TT => '+1-868',
            self::TN => '+216',
            self::TM => '+993',
            self::TC => '+1-649',
            self::TV => '+688',
            self::TR => '+90',
            self::UG => '+256',
            self::UA => '+380',
            self::AE => '+971',
            self::GB => '+44',
            self::US => '+1',
            self::UY => '+598',
            self::UZ => '+998',
            self::VU => '+678',
            self::VE => '+58',
            self::VN => '+84',
            self::VG => '+1-284',
            self::VI => '+1-340',
            self::WF => '+681',
            self::EH => '+212',
            self::YE => '+967',
            self::ZM => '+260',
            self::ZW => '+263',
            self::AX => '+358',
        };
    }

    public function getAlpha3(): string
    {
        return match ($this) {
            self::AF => 'AFG',
            self::AL => 'ALB',
            self::DZ => 'DZA',
            self::AS => 'ASM',
            self::AD => 'AND',
            self::AO => 'AGO',
            self::AI => 'AIA',
            self::AQ => 'ATA',
            self::AG => 'ATG',
            self::AR => 'ARG',
            self::AM => 'ARM',
            self::AW => 'ABW',
            self::AU => 'AUS',
            self::AT => 'AUT',
            self::AZ => 'AZE',
            self::BS => 'BHS',
            self::BH => 'BHR',
            self::BD => 'BGD',
            self::BB => 'BRB',
            self::BY => 'BLR',
            self::BE => 'BEL',
            self::BZ => 'BLZ',
            self::BJ => 'BEN',
            self::BM => 'BMU',
            self::BT => 'BTN',
            self::BO => 'BOL',
            self::BQ => 'BES',
            self::BA => 'BIH',
            self::BW => 'BWA',
            self::BR => 'BRA',
            self::IO => 'IOT',
            self::BN => 'BRN',
            self::BG => 'BGR',
            self::BF => 'BFA',
            self::BI => 'BDI',
            self::CV => 'CPV',
            self::KH => 'KHM',
            self::CM => 'CMR',
            self::CA => 'CAN',
            self::KY => 'CYM',
            self::CF => 'CAF',
            self::TD => 'TCD',
            self::CL => 'CHL',
            self::CN => 'CHN',
            self::CX => 'CXR',
            self::CC => 'CCK',
            self::CO => 'COL',
            self::KM => 'COM',
            self::CG => 'COG',
            self::CD => 'COD',
            self::CK => 'COK',
            self::CR => 'CRI',
            self::HR => 'HRV',
            self::CU => 'CUB',
            self::CW => 'CUW',
            self::CY => 'CYP',
            self::CZ => 'CZE',
            self::CI => 'CIV',
            self::DK => 'DNK',
            self::DJ => 'DJI',
            self::DM => 'DMA',
            self::DO => 'DOM',
            self::EC => 'ECU',
            self::EG => 'EGY',
            self::SV => 'SLV',
            self::GQ => 'GNQ',
            self::ER => 'ERI',
            self::EE => 'EST',
            self::SZ => 'SWZ',
            self::ET => 'ETH',
            self::FK => 'FLK',
            self::FO => 'FRO',
            self::FJ => 'FJI',
            self::FI => 'FIN',
            self::FR => 'FRA',
            self::GF => 'GUF',
            self::PF => 'PYF',
            self::GA => 'GAB',
            self::GM => 'GMB',
            self::GE => 'GEO',
            self::DE => 'DEU',
            self::GH => 'GHA',
            self::GI => 'GIB',
            self::GR => 'GRC',
            self::GL => 'GRL',
            self::GD => 'GRD',
            self::GP => 'GLP',
            self::GU => 'GUM',
            self::GT => 'GTM',
            self::GG => 'GGY',
            self::GN => 'GIN',
            self::GW => 'GNB',
            self::GY => 'GUY',
            self::HT => 'HTI',
            self::VA => 'VAT',
            self::HN => 'HND',
            self::HK => 'HKG',
            self::HU => 'HUN',
            self::IS => 'ISL',
            self::IN => 'IND',
            self::ID => 'IDN',
            self::IR => 'IRN',
            self::IQ => 'IRQ',
            self::IE => 'IRL',
            self::IM => 'IMN',
            self::IL => 'ISR',
            self::IT => 'ITA',
            self::JM => 'JAM',
            self::JP => 'JPN',
            self::JE => 'JEY',
            self::JO => 'JOR',
            self::KZ => 'KAZ',
            self::KE => 'KEN',
            self::KI => 'KIR',
            self::KP => 'PRK',
            self::KR => 'KOR',
            self::XK => 'XKX',
            self::KW => 'KWT',
            self::KG => 'KGZ',
            self::LA => 'LAO',
            self::LV => 'LVA',
            self::LB => 'LBN',
            self::LS => 'LSO',
            self::LR => 'LBR',
            self::LY => 'LBY',
            self::LI => 'LIE',
            self::LT => 'LTU',
            self::LU => 'LUX',
            self::MO => 'MAC',
            self::MG => 'MDG',
            self::MW => 'MWI',
            self::MY => 'MYS',
            self::MV => 'MDV',
            self::ML => 'MLI',
            self::MT => 'MLT',
            self::MH => 'MHL',
            self::MQ => 'MTQ',
            self::MR => 'MRT',
            self::MU => 'MUS',
            self::YT => 'MYT',
            self::MX => 'MEX',
            self::FM => 'FSM',
            self::MD => 'MDA',
            self::MC => 'MCO',
            self::MN => 'MNG',
            self::ME => 'MNE',
            self::MS => 'MSR',
            self::MA => 'MAR',
            self::MZ => 'MOZ',
            self::MM => 'MMR',
            self::NA => 'NAM',
            self::NR => 'NRU',
            self::NP => 'NPL',
            self::NL => 'NLD',
            self::NC => 'NCL',
            self::NZ => 'NZL',
            self::NI => 'NIC',
            self::NE => 'NER',
            self::NG => 'NGA',
            self::NU => 'NIU',
            self::NF => 'NFK',
            self::MK => 'MKD',
            self::MP => 'MNP',
            self::NO => 'NOR',
            self::OM => 'OMN',
            self::PK => 'PAK',
            self::PW => 'PLW',
            self::PS => 'PSE',
            self::PA => 'PAN',
            self::PG => 'PNG',
            self::PY => 'PRY',
            self::PE => 'PER',
            self::PH => 'PHL',
            self::PN => 'PCN',
            self::PL => 'POL',
            self::PT => 'PRT',
            self::PR => 'PRI',
            self::QA => 'QAT',
            self::RO => 'ROU',
            self::RU => 'RUS',
            self::RW => 'RWA',
            self::RE => 'REU',
            self::BL => 'BLM',
            self::SH => 'SHN',
            self::KN => 'KNA',
            self::LC => 'LCA',
            self::MF => 'MAF',
            self::PM => 'SPM',
            self::VC => 'VCT',
            self::WS => 'WSM',
            self::SM => 'SMR',
            self::ST => 'STP',
            self::SA => 'SAU',
            self::SN => 'SEN',
            self::RS => 'SRB',
            self::SC => 'SYC',
            self::SL => 'SLE',
            self::SG => 'SGP',
            self::SX => 'SXM',
            self::SK => 'SVK',
            self::SI => 'SVN',
            self::SB => 'SLB',
            self::SO => 'SOM',
            self::ZA => 'ZAF',
            self::GS => 'SGS',
            self::SS => 'SSD',
            self::ES => 'ESP',
            self::LK => 'LKA',
            self::SD => 'SDN',
            self::SR => 'SUR',
            self::SJ => 'SJM',
            self::SE => 'SWE',
            self::CH => 'CHE',
            self::SY => 'SYR',
            self::TW => 'TWN',
            self::TJ => 'TJK',
            self::TZ => 'TZA',
            self::TH => 'THA',
            self::TL => 'TLS',
            self::TG => 'TGO',
            self::TK => 'TKL',
            self::TO => 'TON',
            self::TT => 'TTO',
            self::TN => 'TUN',
            self::TM => 'TKM',
            self::TC => 'TCA',
            self::TV => 'TUV',
            self::TR => 'TUR',
            self::UG => 'UGA',
            self::UA => 'UKR',
            self::AE => 'ARE',
            self::GB => 'GBR',
            self::US => 'USA',
            self::UY => 'URY',
            self::UZ => 'UZB',
            self::VU => 'VUT',
            self::VE => 'VEN',
            self::VN => 'VNM',
            self::VG => 'VGB',
            self::VI => 'VIR',
            self::WF => 'WLF',
            self::EH => 'ESH',
            self::YE => 'YEM',
            self::ZM => 'ZMB',
            self::ZW => 'ZWE',
            self::AX => 'ALA',
        };
    }

    /**
     * Flags must be published. Returns `<img>` tag.
     */
    public function getFlag(string $class = 'h-5 w-6 shrink-0 object-contain'): HtmlString
    {
        return new HtmlString(
            '<img src="'.e($this->getFlagUrl()).'" alt="" width="32" height="24" class="'.e($class).'" loading="lazy">'
        );
    }

    public function getFlagUrl(): string
    {
        $path = trim((string) config('filament-country-select.flags-path'), '/');

        return asset($path.'/'.$this->value.'.png');
    }

    /** Depending on the reader's system, this might render as the two letters instead of a flag. */
    public function getEmojiFlag(): string
    {
        return collect(mb_str_split($this->value))
            ->map(fn (string $letter): string => mb_chr(0x1F1E6 + ord($letter) - ord('A')))
            ->implode('');
    }

    /**
     * Matched against every shipped language, not just the current locale. Alpha-2 and alpha-3
     * codes, and the names in resources/aliases.php, resolve too.
     */
    public static function tryFromName(?string $name): ?self
    {
        $name = trim((string) $name);

        if ($name === '') {
            return null;
        }

        if ($country = self::tryFrom(Str::upper($name))) {
            return $country;
        }

        return self::tryFrom(self::nameLookup()[Str::lower($name)] ?? '');
    }

    /** @return array<string, string> */
    protected static function nameLookup(): array
    {
        static $lookup = null;

        if ($lookup !== null) {
            return $lookup;
        }

        $lookup = require __DIR__.'/../../resources/aliases.php';

        foreach (glob(__DIR__.'/../../resources/lang/*/countries.php') ?: [] as $file) {
            foreach (require $file as $code => $countryName) {
                $lookup[Str::lower(trim($countryName))] ??= $code;
            }
        }

        foreach (self::cases() as $country) {
            $lookup[Str::lower($country->getAlpha3())] ??= $country->value;
        }

        return $lookup;
    }
}
