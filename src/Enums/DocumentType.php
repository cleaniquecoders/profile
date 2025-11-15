<?php

namespace CleaniqueCoders\Profile\Enums;

use CleaniqueCoders\Traitify\Concerns\InteractsWithEnum;

enum DocumentType: string
{
    use InteractsWithEnum;

    case PASSPORT = 'passport';
    case ID = 'id';
    case DRIVER_LICENSE = 'driver_license';
    case VISA = 'visa';
    case WORK_PERMIT = 'work_permit';
    case CERTIFICATE = 'certificate';
    case DIPLOMA = 'diploma';
    case CONTRACT = 'contract';
    case AGREEMENT = 'agreement';
    case RESUME = 'resume';
    case TAX_DOCUMENT = 'tax_document';
    case INSURANCE = 'insurance';
    case MEDICAL_RECORD = 'medical_record';
    case OTHER = 'other';

    /**
     * Get the label for the document type.
     */
    public function label(): string
    {
        return match ($this) {
            self::PASSPORT => 'Passport',
            self::ID => 'National ID Card',
            self::DRIVER_LICENSE => 'Driver\'s License',
            self::VISA => 'Visa',
            self::WORK_PERMIT => 'Work Permit',
            self::CERTIFICATE => 'Certificate',
            self::DIPLOMA => 'Diploma',
            self::CONTRACT => 'Contract',
            self::AGREEMENT => 'Agreement',
            self::RESUME => 'Resume/CV',
            self::TAX_DOCUMENT => 'Tax Document',
            self::INSURANCE => 'Insurance Policy',
            self::MEDICAL_RECORD => 'Medical Record',
            self::OTHER => 'Other Document',
        };
    }

    /**
     * Get the description for the document type.
     */
    public function description(): string
    {
        return match ($this) {
            self::PASSPORT => 'Passport for international travel',
            self::ID => 'National identification card',
            self::DRIVER_LICENSE => 'Driver\'s license or driving permit',
            self::VISA => 'Travel or work visa',
            self::WORK_PERMIT => 'Work authorization or permit',
            self::CERTIFICATE => 'Certificate or diploma',
            self::DIPLOMA => 'Educational diploma',
            self::CONTRACT => 'Employment or service contract',
            self::AGREEMENT => 'Legal agreement',
            self::RESUME => 'Resume or curriculum vitae',
            self::TAX_DOCUMENT => 'Tax forms and documents',
            self::INSURANCE => 'Insurance policy document',
            self::MEDICAL_RECORD => 'Medical records and reports',
            self::OTHER => 'Other type of document',
        };
    }

    /**
     * Check if this document type typically expires.
     */
    public function typicallyExpires(): bool
    {
        return in_array($this, [
            self::PASSPORT,
            self::ID,
            self::DRIVER_LICENSE,
            self::VISA,
            self::WORK_PERMIT,
            self::CONTRACT,
            self::INSURANCE,
        ]);
    }

    /**
     * Get typical file extensions for this document type.
     */
    public function typicalExtensions(): array
    {
        return match ($this) {
            self::PASSPORT, self::ID, self::DRIVER_LICENSE, self::VISA, self::WORK_PERMIT => ['pdf', 'jpg', 'jpeg', 'png'],
            self::CERTIFICATE, self::DIPLOMA => ['pdf', 'jpg', 'jpeg', 'png'],
            self::CONTRACT, self::AGREEMENT => ['pdf', 'doc', 'docx'],
            self::RESUME => ['pdf', 'doc', 'docx'],
            self::TAX_DOCUMENT => ['pdf', 'xlsx', 'xls'],
            self::INSURANCE => ['pdf'],
            self::MEDICAL_RECORD => ['pdf', 'jpg', 'jpeg', 'png'],
            self::OTHER => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'],
        };
    }
}
