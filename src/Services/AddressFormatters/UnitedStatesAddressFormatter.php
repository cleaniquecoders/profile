<?php

namespace CleaniqueCoders\Profile\Services\AddressFormatters;

class UnitedStatesAddressFormatter extends AbstractAddressFormatter
{
    protected array $states = [
        'Alabama' => 'AL', 'Alaska' => 'AK', 'Arizona' => 'AZ', 'Arkansas' => 'AR',
        'California' => 'CA', 'Colorado' => 'CO', 'Connecticut' => 'CT', 'Delaware' => 'DE',
        'Florida' => 'FL', 'Georgia' => 'GA', 'Hawaii' => 'HI', 'Idaho' => 'ID',
        'Illinois' => 'IL', 'Indiana' => 'IN', 'Iowa' => 'IA', 'Kansas' => 'KS',
        'Kentucky' => 'KY', 'Louisiana' => 'LA', 'Maine' => 'ME', 'Maryland' => 'MD',
        'Massachusetts' => 'MA', 'Michigan' => 'MI', 'Minnesota' => 'MN', 'Mississippi' => 'MS',
        'Missouri' => 'MO', 'Montana' => 'MT', 'Nebraska' => 'NE', 'Nevada' => 'NV',
        'New Hampshire' => 'NH', 'New Jersey' => 'NJ', 'New Mexico' => 'NM', 'New York' => 'NY',
        'North Carolina' => 'NC', 'North Dakota' => 'ND', 'Ohio' => 'OH', 'Oklahoma' => 'OK',
        'Oregon' => 'OR', 'Pennsylvania' => 'PA', 'Rhode Island' => 'RI', 'South Carolina' => 'SC',
        'South Dakota' => 'SD', 'Tennessee' => 'TN', 'Texas' => 'TX', 'Utah' => 'UT',
        'Vermont' => 'VT', 'Virginia' => 'VA', 'Washington' => 'WA', 'West Virginia' => 'WV',
        'Wisconsin' => 'WI', 'Wyoming' => 'WY',
    ];

    public function getCountryCode(): string
    {
        return 'US';
    }

    public function formatPostcode(string $postcode): string
    {
        $cleaned = $this->cleanNumeric($postcode);

        if (strlen($cleaned) === 5) {
            return $cleaned;
        }

        if (strlen($cleaned) === 9) {
            return substr($cleaned, 0, 5).'-'.substr($cleaned, 5);
        }

        return $cleaned;
    }

    public function validatePostcode(string $postcode): bool
    {
        $cleaned = preg_replace('/\s+/', '', $postcode);

        return (bool) preg_match('/^\d{5}(-\d{4})?$/', $cleaned);
    }

    public function getStateAbbreviation(string $state): ?string
    {
        $normalized = mb_convert_case(trim($state), MB_CASE_TITLE);

        return $this->states[$normalized] ?? null;
    }

    public function getStateFullName(string $abbreviation): ?string
    {
        $flipped = array_flip($this->states);

        return $flipped[strtoupper($abbreviation)] ?? null;
    }
}
