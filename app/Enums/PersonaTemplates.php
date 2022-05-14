<?php

namespace App\Enums;

use Doinc\PersonaKyc\Enums\IPersonaTemplates;

enum PersonaTemplates: string implements IPersonaTemplates {
    public function val(): string
    {
        return $this->value;
    }

	case GOVERNMENT_ID = "itmpl_9XC4r2FTEtsuY7aLhVuHJQw3";
	case GOVERNMENT_ID_AND_SELFIE = "itmpl_pFp1GW8kijftMt7YjAHoBNqT";
}
