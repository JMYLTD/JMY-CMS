<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

class geo_ip {
	static $COUNTRY_CODES = array("UN", "AP", "EU", "AD", "AE", "AF", "AG", "AI", "AL", "AM", "AN", "AO", "AQ", "AR", "AS", "AT", "AU", "AW", "AZ", "BA", "BB", "BD", "BE", "BF", "BG", "BH", "BI", "BJ", "BM", "BN", "BO", "BR", "BS", "BT", "BV", "BW", "BY", "BZ", "CA", "CC", "CD", "CF", "CG", "CH", "CI", "CK", "CL", "CM", "CN", "CO", "CR", "CU", "CV", "CX", "CY", "CZ", "DE", "DJ", "DK", "DM", "DO", "DZ", "EC", "EE", "EG", "EH", "ER", "ES", "ET", "FI", "FJ", "FK", "FM", "FO", "FR", "FX", "GA", "GB", "GD", "GE", "GF", "GH", "GI", "GL", "GM", "GN", "GP", "GQ", "GR", "GS", "GT", "GU", "GW", "GY", "HK", "HM", "HN", "HR", "HT", "HU", "ID", "IE", "IL", "IN", "IO", "IQ", "IR", "IS", "IT", "JM", "JO", "JP", "KE", "KG", "KH", "KI", "KM", "KN", "KP", "KR", "KW", "KY", "KZ", "LA", "LB", "LC", "LI", "LK", "LR", "LS", "LT", "LU", "LV", "LY", "MA", "MC", "MD", "MG", "MH", "MK", "ML", "MM", "MN", "MO", "MP", "MQ", "MR", "MS", "MT", "MU", "MV", "MW", "MX", "MY", "MZ", "NA", "NC", "NE", "NF", "NG", "NI", "NL", "NO", "NP", "NR", "NU", "NZ", "OM", "PA", "PE", "PF", "PG", "PH", "PK", "PL", "PM", "PN", "PR", "PS", "PT", "PW", "PY", "QA", "RE", "RO", "RU", "RW", "SA", "SB", "SC", "SD", "SE", "SG", "SH", "SI", "SJ", "SK", "SL", "SM", "SN", "SO", "SR", "ST", "SV", "SY", "SZ", "TC", "TD", "TF", "TG", "TH", "TJ", "TK", "TM", "TN", "TO", "TP", "TR", "TT", "TV", "TW", "TZ", "UA", "UG", "UM", "US", "UY", "UZ", "VA", "VC", "VE", "VG", "VI", "VN", "VU", "WF", "WS", "YE", "YT", "YU", "ZA", "ZM", "ZR", "ZW", "A1", "A2", "O1");

	static $COUNTRY_NAMES = array("Неизвестно", "Азия/Тихоокеанский регион", "Европа", "Андорра", "Объединенные Арабские Эмираты", "Афганистан", "Антигуа и Барбуда", "Ангилья", "Албания", "Армения", "Нидерландские Антильские острова", "Ангола", "Антарктида", "Аргентина", "американские Острова Самоа", "Австрия", "Австралия", "Аруба", "Азербайджан", "Босния и Герцеговина", "Барбадос", "Бангладеш", "Бельгия", "Буркина-Фасо", "Болгария", "Бахрейн", "Бурунди", "Бенин", "Бермуды", "Бруней Darussalam", "Боливия", "Бразилия", "Багамы", "Бутан", "Остров Bouvet", "Ботсвана", "Белоруссия", "Белиз", "Канада", "Кокосовые острова (острова Килинг)", "Конго, демократическая республика", "Центральноафриканская Республика", "Конго", "Швейцария", "Кот-д'Ивуар", "Острова Кука", "Чили", "Камерун", "Китай", "Колумбия", "Коста-Рика", "Куба", "Зеленый мыс", "Остров Рождества", "Кипр", "Чешская республика", "Германия", "Джибути", "Дания", "Доминиканская республика", "Доминиканская Республика", "Алжир", "Эквадор", "Эстония", "Египет", "Западная Сахара", "Эритрея", "Испания", "Эфиопия", "Финляндия", "Фиджи", "Фолклендские острова (Malvinas)", "Микронезия, Объединенные государства", "Острова Faroe", "Франция", "Франция, Столичная", "Габон", "Соединенное Королевство", "Гренада", "Джорджия", "французская Гвиана", "Гана", "Гибралтар", "Остров Гренландия", "Гамбия", "Гвинея", "Остров Гваделупа", "Экваториальная Гвинея", "Греция", "Южная Георгия и Южные Сандвичевы острова", "Гватемала", "Гуам", "Гвинея - Бисау", "Гайана", "Гонконг", "Услышал Острова Острова и McDonald", "Гондурас", "Хорватия", "Гаити", "Венгрия", "Индонезия", "Ирландия", "Израиль", "Индия", "британская Территория Индийского океана", "Ирак", "Иран", "Исландия", "Италия", "Ямайка", "Иордания", "Япония", "Кения", "Кыргызстан", "Камбоджа", "Кирибати", "Коморские острова", "Сент-Киттс и Nevis", "Северная Корея", "Южная Корея", "Кувейт", "Каймановы острова", "Казахстан", "Народная демократическая республика Lao", "Ливан", "Сент-Люсия", "Лихтенштейн", "Шри-Ланка", "Либерия", "Лесото", "Литва", "Люксембург", "Латвия", "ливийский арабский Jamahiriya", "Марокко", "Монако", "Молдова", "Мадагаскар", "Маршалловы острова", "Македония", "Mali", "Myanmar", "Монголия", "Macau", "Северные Марианские острова", "Мартиника", "Мавритания", "Монтсеррат", "Мальта", "Маврикий", "Мальдивы", "Малави", "Мексика", "Малайзия", "Мозамбик", "Намибия", "Новая Каледония", "Нигер", "Остров Норфолк", "Нигерия", "Никарагуа", "Нидерланды", "Норвегия", "Непал", "Науру", "Ниуэ", "Новая Зеландия", "Оман", "Панама", "Перу", "Французская Полинезия", "Папуа-Новая Гвинея", "Филиппины", "Пакистан", "Польша", "Сен-Пьер и Микелон", "Острова Pitcairn", "Пуэрто-Рико", "палестинская Территория, Занятая", "Португалия", "Палау", "Парагвай", "Катар", "Воссоединение", "Румыния", "Россия", "Руанда", "Саудовская Аравия", "Соломоновы Острова", "Сейшельские острова", "Судан", "Швеция", "Сингапур", "Остров Святой Елены", "Словения", "Svalbard и Ян Mayen", "Словакия", "Сьерра-Леоне", "Сан-Марино", "Сенегал", "Сомали", "Suriname", "Сао Том и Principe", "Сальвадор", "сирийская арабская республика", "Свазиленд", "Острова Теркс и Кайкос", "Чад", "Французские Южные Территории", "Того", "Таиланд", "Таджикистан", "Токелау", "Туркмения", "Тунис", "Тонга", "Восточный Тимор", "Турция", "Тринидад и Тобаго", "Тувалу", "Tайвань", "Танзания, Объединенная республика", "Украина", "Уганда", "Незначительные Отдаленные Острова Соединенных Штатов", "США", "Уругвай", "Узбекистан", "Папский престол (Ватиканское государство)", "Сент-Винсент и Гренадины", "Венесуэла", "Виргинские острова, британцы", "Виргинские острова, США", "Вьетнам", "Вануату", "Wallis и Futuna", "Острова Самоа", "Йемен", "Mayotte", "Босния и Герцеговина", "Южная Африка", "Замбия", "Заир", "Зимбабве", "Анонимное Полномочие", "Спутниковый Поставщик", "Другой");

	const STANDARD = 0;
	const MEMORY_CACHE = 1;
	const SHARED_MEMORY = 2;
	const COUNTRY_BEGIN = 16776960;
	const STATE_BEGIN_REV0 = 16700000;
	const STATE_BEGIN_REV1 = 16000000;
	const STRUCTURE_INFO_MAX_SIZE = 20;
	const DATABASE_INFO_MAX_SIZE = 100;
	const COUNTRY_EDITION = 106;
	const REGION_EDITION_REV0 = 112;
	const REGION_EDITION_REV1 = 3;
	const CITY_EDITION_REV0 = 111;
	const CITY_EDITION_REV1 = 2;
	const ORG_EDITION = 110;
	const SEGMENT_RECORD_LENGTH = 3;
	const STANDARD_RECORD_LENGTH = 3;
	const ORG_RECORD_LENGTH = 4;
	const MAX_RECORD_LENGTH = 4;
	const MAX_ORG_RECORD_LENGTH = 300;
	const FULL_RECORD_LENGTH = 50;
	const US_OFFSET = 1;
	const CANADA_OFFSET = 677;
	const WORLD_OFFSET = 1353;
	const FIPS_RANGE = 360;
	const SHM_KEY = 0x4f415401;

	private $flags = 0;
	private $filehandle;
	private $memoryBuffer;
	private $databaseType;
	private $databaseSegments;
	private $recordLength;
	private $shmid;
	private static $instances = array();

	function __construct($filename = null, $flags = null) {
		if ($filename !== null) {
			$this->open($filename, $flags);
		}
		self::$instances[$filename] = $this;
	}
	static function getInstance($filename = null, $flags = null) {
		if (!isset(self::$instances[$filename])) {
			self::$instances[$filename] = new geo_ip($filename, $flags);
		}
		return self::$instances[$filename];
	}
	function open($filename, $flags = null) {
		if ($flags !== null) {
			$this->flags = $flags;
		}
		if ($this->flags & self::SHARED_MEMORY) {
			$this->shmid = @shmop_open(self::SHM_KEY, "a", 0, 0);
			if ($this->shmid === false) {
				$this->loadSharedMemory($filename);
				$this->shmid = @shmop_open(self::SHM_KEY, "a", 0, 0);
				if ($this->shmid === false) {
					throw new Exception("Невозможно использовать ключ памяти: " . dechex(self::SHM_KEY));
				}
			}
		} else {
			$this->filehandle = fopen($filename, "rb");
			if (!$this->filehandle) {
				throw new Exception("Невозможно открыть: $filename");
			}
			if ($this->flags & self::MEMORY_CACHE) {
				$s_array = fstat($this->filehandle);
				$this->memoryBuffer = fread($this->filehandle, $s_array['size']);
			}
		}
		$this->setupSegments();
	}
	private function loadSharedMemory($filename) {
		$fp = fopen($filename, "rb");
		if (!$fp) {
			throw new Exception("Невозможно открыть: $filename");
		}
		$s_array = fstat($fp);
		$size = $s_array['size'];
		if ($shmid = shmop_open(self::SHM_KEY, "w", 0, 0)) {
			shmop_delete ($shmid);
			shmop_close ($shmid);
		}
		$shmid = shmop_open(self::SHM_KEY, "c", 0644, $size);
		shmop_write($shmid, fread($fp, $size), 0);
		shmop_close($shmid);
		fclose($fp);
	}
	private function setupSegments() {
		$this->databaseType = self::COUNTRY_EDITION;
		$this->recordLength = self::STANDARD_RECORD_LENGTH;
		if ($this->flags & self::SHARED_MEMORY) {
			$offset = shmop_size($this->shmid) - 3;
			for ($i = 0; $i < self::STRUCTURE_INFO_MAX_SIZE; $i++) {
				$delim = shmop_read($this->shmid, $offset, 3);
				$offset += 3;
				if ($delim == (chr(255).chr(255).chr(255))) {
					$this->databaseType = ord(shmop_read($this->shmid, $offset, 1));
					$offset++;
					if ($this->databaseType === self::REGION_EDITION_REV0) {
						$this->databaseSegments = self::STATE_BEGIN_REV0;
					} elseif ($this->databaseType === self::REGION_EDITION_REV1) {
						$this->databaseSegments = self::STATE_BEGIN_REV1;
					} elseif (($this->databaseType === self::CITY_EDITION_REV0) || ($this->databaseType === self::CITY_EDITION_REV1) || ($this->databaseType === self::ORG_EDITION)) {
						$this->databaseSegments = 0;
						$buf = shmop_read($this->shmid, $offset, self::SEGMENT_RECORD_LENGTH);
						for ($j = 0; $j < self::SEGMENT_RECORD_LENGTH; $j++) {
							$this->databaseSegments += (ord($buf[$j]) << ($j * 8));
						}
						if ($this->databaseType === self::ORG_EDITION) {
							$this->recordLength = self::ORG_RECORD_LENGTH;
						}
					}
					break;
				} else {
					$offset -= 4;
				}
			}
			if ($this->databaseType == self::COUNTRY_EDITION) {
				$this->databaseSegments = self::COUNTRY_BEGIN;
			}
		} else {
			$filepos = ftell($this->filehandle);
			fseek($this->filehandle, -3, SEEK_END);
			for ($i = 0; $i < self::STRUCTURE_INFO_MAX_SIZE; $i++) {
				$delim = fread($this->filehandle, 3);
				if ($delim == (chr(255).chr(255).chr(255))) {
					$this->databaseType = ord(fread($this->filehandle,1));
					if ($this->databaseType === self::REGION_EDITION_REV0) {
						$this->databaseSegments = self::STATE_BEGIN_REV0;
					} elseif($this->databaseType === self::REGION_EDITION_REV1) {
						$this->databaseSegments = self::STATE_BEGIN_REV1;
					} elseif ($this->databaseType === self::CITY_EDITION_REV0 || $this->databaseType === self::CITY_EDITION_REV1 || $this->databaseType === self::ORG_EDITION) {
						$this->databaseSegments = 0;
						$buf = fread($this->filehandle, self::SEGMENT_RECORD_LENGTH);
						for ($j = 0; $j < self::SEGMENT_RECORD_LENGTH; $j++) {
							$this->databaseSegments += (ord($buf[$j]) << ($j * 8));
						}
						if ($this->databaseType === self::ORG_EDITION) {
							$this->recordLength = self::ORG_RECORD_LENGTH;
						}
					}
					break;
				} else {
					fseek($this->filehandle, -4, SEEK_CUR);
				}
			}
			if ($this->databaseType === self::COUNTRY_EDITION) {
				$this->databaseSegments = self::COUNTRY_BEGIN;
			}
			fseek($this->filehandle, $filepos, SEEK_SET);
		}
	}
	private function lookupCountryId($addr) {
		$ipnum = ip2long($addr);
		if ($ipnum === false) {
			throw new Exception("Ошибочный IP: " . var_export($addr, true));
		}
		if ($this->databaseType !== self::COUNTRY_EDITION) {
			throw new Exception("Неверный тип базы данных; lookupCountry * () методы ожидают базу данных страны.");
		}
		return $this->seekCountry($ipnum) - self::COUNTRY_BEGIN;
	}
	function lookupCountryCode($addr) {
		return self::$COUNTRY_CODES[$this->lookupCountryId($addr)];
	}
	function lookupCountryName($addr) {
		return self::$COUNTRY_NAMES[$this->lookupCountryId($addr)];
	}
	private function seekCountry($ipnum) {
		$offset = 0;
		for ($depth = 31; $depth >= 0; --$depth) {
			if ($this->flags & self::MEMORY_CACHE) {
				$buf = mb_substr($this->memoryBuffer, 2 * $this->recordLength * $offset, 2 * $this->recordLength);
			} elseif ($this->flags & self::SHARED_MEMORY) {
				$buf = shmop_read ($this->shmid, 2 * $this->recordLength * $offset, 2 * $this->recordLength );
			} else {
				if (fseek($this->filehandle, 2 * $this->recordLength * $offset, SEEK_SET) !== 0) {
					throw new Exception("fseek failed");
				}
				$buf = fread($this->filehandle, 2 * $this->recordLength);
			}
			$x = array(0,0);
			for ($i = 0; $i < 2; ++$i) {
				for ($j = 0; $j < $this->recordLength; ++$j) {
					$x[$i] += ord($buf[$this->recordLength * $i + $j]) << ($j * 8);
				}
			}
			if ($ipnum & (1 << $depth)) {
				if ($x[1] >= $this->databaseSegments) {
					return $x[1];
				}
				$offset = $x[1];
			} else {
				if ($x[0] >= $this->databaseSegments) {
					return $x[0];
				}
				$offset = $x[0];
			}
		}
		throw new Exception("Ошибка чтения базы данных - возможно, она повреждена?");
	}
}