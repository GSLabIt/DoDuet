<?php

namespace App\Enums;

use Doinc\PersonaKyc\Enums\IPersonaTemplates;

enum PersonaTemplates: string implements IPersonaTemplates {
    public function val(): string
    {
        return $this->value;
    }

	case GOVERNMENT_ID = "itmplv_tGpKFGNG9dsvZS5QSHr9P7Zz";
	case GOVERNMENT_ID_AND_SELFIE = "itmpl_qsPvbUsTWT1fv6wWPSM3pjXB";
}
