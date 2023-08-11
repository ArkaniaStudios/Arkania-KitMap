<?php

/*
 *
 *     _      ____    _  __     _      _   _   ___      _                 _   _   _____   _____  __        __   ___    ____    _  __
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \               | \ | | | ____| |_   _| \ \      / /  / _ \  |  _ \  | |/ /
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \     _____    |  \| | |  _|     | |    \ \ /\ / /  | | | | | |_) | | ' /
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \   |_____|   | |\  | | |___    | |     \ V  V /   | |_| | |  _ <  | . \
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\            |_| \_| |_____|   |_|      \_/\_/     \___/  |_| \_\ |_|\_\
 *
 * Arkania is a Minecraft Bedrock server created in 2019,
 * we mainly use PocketMine-MP to create content for our server
 * but we use something else like WaterDog PE
 *
 * @author Arkania-Team
 * @link https://arkaniastudios.com
 *
 */

declare(strict_types=1);

namespace arkania\utils\trait;

class Date {
	private bool $viewDay = false;
	private bool $viewMonth = false;
	private bool $viewYear = false;
	private bool $viewHour = false;
	private bool $viewMinute = false;
	private bool $viewDayNumber = false;
	private string $day;
	private string $month;
	private string $year;
	private string $hour;
	private string $minute;
	private string $day_number;

    public function __construct() {
        $jours = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        $month = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        $jour_number = date('w');
        $mois_number = date('m');
        $heure = date('H');
        $minute = date('i');
        $annee = date('Y');
        $this->year = $annee;
        $this->hour = $heure;
        $this->minute = $minute;
        $this->day = $jours[$jour_number];
        $this->month = $month[$mois_number - 1];
        $this->day_number = date('d');
    }

    public static function create() : self {
		return new self();
	}

	public function toString() : string {
		if ($this->viewDayNumber && $this->viewDay && $this->viewMonth && $this->viewYear && $this->viewHour && $this->viewMinute) {
			return $this->day_number . " " . $this->day . " " . $this->month . " " . $this->year . " à " . $this->hour . "H" . $this->minute;
		}
		if ($this->viewDayNumber && $this->viewDay && $this->viewMonth && $this->viewYear && $this->viewHour) {
			return $this->day_number . " " . $this->day . " " . $this->month . " " . $this->year . " à " . $this->hour . "H" . $this->minute;
		}
		if ($this->viewDayNumber && $this->viewDay && $this->viewMonth && $this->viewHour) {
			return $this->day_number . " " . $this->day . " " . $this->month . " à " . $this->hour . "H" . $this->minute;
		}
		if ($this->viewDayNumber && $this->viewDay && $this->viewMonth && $this->viewYear) {
			return $this->day_number . " " . $this->day . " " . $this->month . " " . $this->year;
		}
		if ($this->viewDayNumber && $this->viewDay && $this->viewHour) {
			return $this->day_number . " " . $this->day . " à " . $this->hour . "H" . $this->minute;
		}
		if ($this->viewDayNumber && $this->viewDay && $this->viewYear) {
			return $this->day_number . " " . $this->day . " " . $this->year;
		}
		if ($this->viewDayNumber && $this->viewDay && $this->viewMonth) {
			return $this->day_number . " " . $this->day . " " . $this->month;
		}
		if ($this->viewDayNumber && $this->viewDay) {
			return $this->day_number . " " . $this->day;
		}
		if ($this->viewHour && $this->viewMinute) {
			return $this->hour . "H" . $this->minute;
		}
		if ($this->viewMonth && $this->viewHour) {
			return $this->month . " à " . $this->hour . "H" . $this->minute;
		}
		if ($this->viewMonth && $this->viewDayNumber) {
			return $this->month . " " . $this->day_number;
		}
		if ($this->viewYear && $this->viewHour) {
			return $this->year . " à " . $this->hour . "H" . $this->minute;
		}
		if ($this->viewYear && $this->viewDayNumber) {
			return $this->year . " " . $this->day_number;
		}
		if ($this->viewMonth && $this->viewYear) {
			return $this->month . " " . $this->year;
		}
		if ($this->viewDay && $this->viewDayNumber) {
			return $this->day . " " . $this->day_number;
		}
		if ($this->viewDayNumber && $this->viewHour) {
			return $this->day_number . " à " . $this->hour . "H" . $this->minute;
		}
		if ($this->viewDayNumber && $this->viewMinute) {
			return $this->day_number . " " . $this->minute;
		}
		if ($this->viewDayNumber && $this->viewYear) {
			return $this->day_number . " " . $this->year;
		}
		if ($this->viewDayNumber && $this->viewMonth) {
			return $this->day_number . " " . $this->month;
		}
		if ($this->viewDay && $this->viewHour) {
			return $this->day . " à " . $this->hour . "H" . $this->minute;
		}
		if ($this->viewDay && $this->viewYear) {
			return $this->day . " " . $this->year;
		}
		if ($this->viewDay && $this->viewMonth) {
			return $this->day . " " . $this->month;
		}
		if ($this->viewDay) {
			return $this->day;
		}
		if ($this->viewMonth) {
			return $this->month;
		}
		if ($this->viewYear) {
			return $this->year;
		}
		if ($this->viewHour) {
			return $this->hour;
		}
		if ($this->viewMinute) {
			return $this->minute;
		}
		if ($this->viewDayNumber) {
			return $this->day_number;
		}

		return $this->day . " " . $this->day_number . " " . $this->month . " " . $this->year . " à " . $this->hour . "H" . $this->minute;
	}

	public function viewDay() : self {
		$this->viewDay = true;
		return $this;
	}

	public function viewMonth() : self {
		$this->viewMonth = true;
		return $this;
	}

	public function viewYear() : self {
		$this->viewYear = true;
		return $this;
	}

	public function viewHour() : self {
		$this->viewHour = true;
		return $this;
	}

	public function viewMinute() : self {
		$this->viewMinute = true;
		return $this;
	}

	public function viewDayNumber() : self {
		$this->viewDayNumber = true;
		return $this;
	}

	public function setTimeZone(string $timeZone) : self {
		date_default_timezone_set($timeZone);
		return $this;
	}
}
