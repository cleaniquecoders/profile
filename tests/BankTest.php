<?php

namespace CleaniqueCoders\Profile\Tests;

use Illuminate\Support\Facades\Schema;

class BankTest extends TestCase
{
    /**
     * @var string
     */
    protected $get_actual_config_key = 'bank';

    /**
     * @var string
     */
    protected $get_expected_config_key = 'bank';

    /**
     * @var string
     */
    protected $get_actual_config_model_class = '\CleaniqueCoders\Profile\Models\Bank::class';

    /**
     * @var string
     */
    protected $get_expected_config_model_class = '\CleaniqueCoders\Profile\Models\Bank::class';

    /**
     * @var string
     */
    protected $get_actual_config_type = 'bankable';

    /**
     * @var string
     */
    protected $get_expected_config_type = 'bankable';

    /** @test */
    public function it_has_banks_table()
    {
        $this->assertTrue(Schema::hasTable('banks'));
    }

    /** @test */
    public function it_has_bank_accounts_table()
    {
        $this->assertTrue(Schema::hasTable('bank_accounts'));
    }

    /** @test */
    public function it_has_banks_data()
    {
        $banks = [
            ['name' => 'AFFIN BANK BERHAD', 'swift_code' => 'PHBMMYKL', 'bank_code' => 'PHBM'],
            ['name' => 'AFFIN HWANG INVESTMENT BANK BERHAD', 'swift_code' => 'HDSBMY2P', 'bank_code' => 'HDSB'],
            ['name' => 'AFFIN ISLAMIC BANK BERHAD', 'swift_code' => 'AIBBMYKL', 'bank_code' => 'AIBB'],
            ['name' => 'AIA BHD.', 'swift_code' => 'AIACMYKL', 'bank_code' => 'AIAC'],
            ['name' => 'BANK PERTANIAN MALAYSIA BERHAD - AGROBANK', 'swift_code' => 'AGOBMYKL', 'bank_code' => 'AGOB'],
            ['name' => 'AL RAJHI BANKING AND INVESTMENT CORPORATION (MALAYSIA) BHD', 'swift_code' => 'RJHIMYKL', 'bank_code' => 'RJHI'],
            ['name' => 'ALKHAIR INTERNATIONAL ISLAMIC BANK BERHAD', 'swift_code' => 'UIIBMYKL', 'bank_code' => 'UIIB'],
            ['name' => 'ALLIANCE BANK MALAYSIA BERHAD', 'swift_code' => 'MFBBMYKL', 'bank_code' => 'MFBB'],
            ['name' => 'ALLIANCE INVESTMENT BANK BERHAD', 'swift_code' => 'MBAMMYKL', 'bank_code' => 'MBAM'],
            ['name' => 'ALLIANCE ISLAMIC BANK BERHAD', 'swift_code' => 'ALSRMYKL', 'bank_code' => 'ALSR'],
            ['name' => 'AMBANK (M) BERHAD', 'swift_code' => 'ARBKMYKL', 'bank_code' => 'ARBK'],
            ['name' => 'AMBANK (M) BERHAD LABUAN OFFSHORE BRANCH', 'swift_code' => 'ARBKMYKLLAB', 'bank_code' => 'ARBK'],
            ['name' => 'AMINVESTMENT BANK BERHAD', 'swift_code' => 'AMMBMYKL', 'bank_code' => 'AMMB'],
            ['name' => 'AMISLAMIC BANK BERHAD', 'swift_code' => 'AISLMYKL', 'bank_code' => 'AISL'],
            ['name' => 'ASIAN FINANCE BANK BERHAD', 'swift_code' => 'AFBQMYKL', 'bank_code' => 'AFBQ'],
            ['name' => 'ASIAN TRADE INVESTMENT BANK LTD', 'swift_code' => 'ATBLMY2A', 'bank_code' => 'ATBL'],
            ['name' => 'BANGKOK BANK BERHAD', 'swift_code' => 'BKKBMYKL', 'bank_code' => 'BKKB'],
            ['name' => 'BANK AL HABIB LIMITED', 'swift_code' => 'BAHLMY2A', 'bank_code' => 'BAHL'],
            ['name' => 'BANK ISLAM MALAYSIA BERHAD', 'swift_code' => 'BIMBMYKL', 'bank_code' => 'BIMB'],
            ['name' => 'BANK ISLAM MALAYSIA BERHAD LABUAN OFFSHORE BRANCH', 'swift_code' => 'BISLMYKA', 'bank_code' => 'BISL'],
            ['name' => 'BANK MUAMALAT MALAYSIA BERHAD', 'swift_code' => 'BMMBMYKL', 'bank_code' => 'BMMB'],
            ['name' => 'BANK MUAMALAT MALAYSIA BERHAD (TRADE FINANCE)', 'swift_code' => 'BMMBMYKLTFD', 'bank_code' => 'BMMB'],
            ['name' => 'BANK NEGARA MALAYSIA (HEAD OFFICE)', 'swift_code' => 'BNMAMYKL', 'bank_code' => 'BNMA'],
            ['name' => 'BANK NEGARA MALAYSIA', 'swift_code' => 'BNMAMY2K', 'bank_code' => 'BNMA'],
            ['name' => 'BANK OF AMERICA, MALAYSIA BERHAD', 'swift_code' => 'BOFAMY2X', 'bank_code' => 'BOFA'],
            ['name' => 'BANK OF AMERICA, MALAYSIA BERHAD', 'swift_code' => 'BOFAMY2XGRC', 'bank_code' => 'BOFA'],
            ['name' => 'BANK OF AMERICA, MALAYSIA BERHAD', 'swift_code' => 'BOFAMY2XLMY', 'bank_code' => 'BOFA'],
            ['name' => 'BANK OF AMERICA, MALAYSIA BERHAD', 'swift_code' => 'BOFAMY2XLBN', 'bank_code' => 'BOFA'],
            ['name' => 'BANK OF CHINA (MALAYSIA) BERHAD', 'swift_code' => 'BKCHMYKL', 'bank_code' => 'BKCH'],
            ['name' => 'BANK OF TOKYO-MITSUBISHI UFJ (MALAYSIA) BERHAD', 'swift_code' => 'BOTKMYKX', 'bank_code' => 'BOTK'],
            ['name' => 'BANK KERJASAMA RAKYAT MALAYSIA BERHAD (BANK RAKYAT)', 'swift_code' => 'BKRMMYKL', 'bank_code' => 'BKRM'],
            ['name' => 'Bank Simpanan Malaysia', 'swift_code' => '', 'bank_code' => ''],
            ['name' => 'BNP PARIBAS', 'swift_code' => 'BNPAMYKA', 'bank_code' => 'BNPA'],
            ['name' => 'BNP PARIBAS MALAYSIA BERHAD', 'swift_code' => 'BNPAMYKL', 'bank_code' => 'BNPA'],
            ['name' => 'BNP PARIBAS MALAYSIA BERHAD (BNPPM ISLAMIC BANKING WINDOW)', 'swift_code' => 'BNPAMYKLSPI', 'bank_code' => 'BNPA'],
            ['name' => 'CAGAMAS BERHAD', 'swift_code' => '', 'bank_code' => ''],
            ['name' => 'CIMB BANK BERHAD', 'swift_code' => 'CIBBMYKL', 'bank_code' => 'CIBB'],
            ['name' => 'CIMB BANK BERHAD (SECURITIES BORROWING AND LENDING)', 'swift_code' => 'CIBBMYKLSBL', 'bank_code' => 'CIBB'],
            ['name' => 'CIMB BANK (L) LIMITED', 'swift_code' => 'CIBBMYKA', 'bank_code' => 'CIBB'],
            ['name' => 'CIMB BANK BERHAD, LABUAN OFFSHORE BRANCH', 'swift_code' => 'CIBBMY2L', 'bank_code' => 'CIBB'],
            ['name' => 'CIMB ISLAMIC BANK BERHAD', 'swift_code' => 'CTBBMYKL', 'bank_code' => 'CTBB'],
            ['name' => 'CIMB-PRINCIPAL ASSET MANAGEMENT BERHAD', 'swift_code' => 'CANHMYKL', 'bank_code' => 'CANH'],
            ['name' => 'CITIBANK BERHAD (JALAN AMPANG)', 'swift_code' => 'CITIMYKL', 'bank_code' => 'CITI'],
            ['name' => 'CITIBANK BERHAD (PENANG BRANCH)', 'swift_code' => 'CITIMYKLPEN', 'bank_code' => 'CITI'],
            ['name' => 'CITIBANK BERHAD (JOHOR BAHRU BRANCH)', 'swift_code' => 'CITIMYKLJOD', 'bank_code' => 'CITI'],
            ['name' => 'CITIBANK BERHAD (LAB)', 'swift_code' => ' CITIMYKLLAB', 'bank_code' => 'CITI'],
            ['name' => 'DBS BANK LTD, LABUAN BRANCH', 'swift_code' => 'DBSSMY2A', 'bank_code' => 'DBSS'],
            ['name' => 'DEUTSCHE BANK (MALAYSIA) BERHAD', 'swift_code' => 'DEUTMYKL', 'bank_code' => 'DEUT'],
            ['name' => 'DEUTSCHE BANK (MALAYSIA) BERHAD', 'swift_code' => 'DEUTMYKLGMO', 'bank_code' => 'DEUT'],
            ['name' => 'DEUTSCHE BANK (MALAYSIA) BERHAD', 'swift_code' => 'DEUTMYKLIBW', 'bank_code' => 'DEUT'],
            ['name' => 'DEUTSCHE BANK (MALAYSIA) BERHAD (INTERNATIONAL ISLAMIC BANKING, MALAYSIA BRANCH)', 'swift_code' => 'DEUTMYKLISB', 'bank_code' => 'DEUT'],
            ['name' => 'DEUTSCHE BANK (MALAYSIA) BERHAD (LABUAN BRANCH)', 'swift_code' => 'DEUTMYKLBLB', 'bank_code' => 'DEUT'],
            ['name' => 'EXPORT-IMPORT BANK OF MALAYSIA BERHAD', 'swift_code' => 'EXMBMYKL', 'bank_code' => 'EXMB'],
            ['name' => 'FELDA GLOBAL VENTURES CAPITAL SDN. BHD.', 'swift_code' => 'FGVCMYKL', 'bank_code' => 'FGVC'],
            ['name' => 'FIDELITY ASIA BANK LTD', 'swift_code' => 'FABEMY22', 'bank_code' => 'FABE'],
            ['name' => 'HONG LEONG BANK BERHAD', 'swift_code' => 'HLBBMYKL', 'bank_code' => 'HLBB'],
            ['name' => 'HONG LEONG BANK BERHAD (ISLAMIC BANKING UNIT)', 'swift_code' => 'HLBBMYKLIBU', 'bank_code' => 'HLBB'],
            ['name' => 'HONG LEONG BANK BERHAD, JOHOR BAHRU, JOHOR', 'swift_code' => 'HLBBMYKLJBU', 'bank_code' => 'HLBB'],
            ['name' => 'HONG LEONG BANK BERHAD, KUCHING, SARAWAK', 'swift_code' => 'HLBBMYKLKCH', 'bank_code' => 'HLBB'],
            ['name' => 'HONG LEONG BANK BERHAD, PENANG, PENANG', 'swift_code' => 'HLBBMYKLPNG', 'bank_code' => 'HLBB'],
            ['name' => 'HONG LEONG INVESTMENT BANK BERHAD', 'swift_code' => 'HLIVMYKL', 'bank_code' => 'HLIV'],
            ['name' => 'HONG LEONG ISLAMIC BANK BERHAD', 'swift_code' => 'HLIBMYKL', 'bank_code' => 'HLIB'],
            ['name' => 'THE HONGKONG AND SHANGHAI BANKING CORPORATION LTD.', 'swift_code' => 'HSBCMYKA', 'bank_code' => 'HSBC'],
            ['name' => 'HSBC (MALAYSIA) TRUSTEE BERHAD', 'swift_code' => 'HSTMMYKL', 'bank_code' => 'HSTM'],
            ['name' => 'HSBC (MALAYSIA) TRUSTEE BERHAD (GLOBAL WEALTH SOLUTIONS)', 'swift_code' => 'HSTMMYKLGWS', 'bank_code' => 'HSTM'],
            ['name' => 'HSBC AMANAH MALAYSIA BERHAD', 'swift_code' => 'HMABMYKL', 'bank_code' => 'HMAB'],
            ['name' => 'HSBC BANK MALAYSIA BERHAD', 'swift_code' => 'HBMBMYKL', 'bank_code' => 'HBMB'],
            ['name' => 'INDIA INTERNATIONAL BANK (MALAYSIA) BERHAD', 'swift_code' => 'IIMBMYKL', 'bank_code' => 'IIMB'],
            ['name' => 'INDUSTRIAL AND COMMERCIAL BANK OF CHINA (MALAYSIA) BERHAD.', 'swift_code' => 'ICBKMYKL', 'bank_code' => 'ICBK'],
            ['name' => 'INDUSTRIAL AND COMMERCIAL BANK OF CHINA (MALAYSIA) BERHAD., (LABUAN BRANCH)', 'swift_code' => 'ICBKMYKLLAB', 'bank_code' => 'ICBK'],
            ['name' => 'J.P.MORGAN CHASE BANK BERHAD', 'swift_code' => 'CHASMYKX', 'bank_code' => 'CHAS'],
            ['name' => 'J.P.MORGAN CHASE BANK BERHAD, (JPMORGAN SECURITIES - MALAYSIA)', 'swift_code' => 'CHASMYKXSEC', 'bank_code' => 'CHAS'],
            ['name' => 'J.P.MORGAN CHASE BANK BERHAD, (TEST KEY AND BKE ADMINISTRATION)', 'swift_code' => 'CHASMYKXKEY', 'bank_code' => 'CHAS'],
            ['name' => 'JPMORGAN CHASE BANK, N.A., LABUAN BRANCH', 'swift_code' => 'CHASMY2A', 'bank_code' => 'CHAS'],
            ['name' => 'KAF INVESTMENT BANK BERHAD', 'swift_code' => 'KAFBMYKL', 'bank_code' => 'KAFB'],
            ['name' => 'KENANGA INVESTMENT BANK BERHAD', 'swift_code' => 'ECMLMYKL', 'bank_code' => 'ECML'],
            ['name' => 'KUWAIT FINANCE HOUSE (MALAYSIA) BERHAD', 'swift_code' => 'KFHOMYKL', 'bank_code' => 'KFHO'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK)', 'swift_code' => 'MBBEMYKL', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), BUTTERWORTH, PENANG (TRADE FINANCE CENTER) ', 'swift_code' => 'MBBEMYKLBWC', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), IPOH, PERAK', 'swift_code' => 'MBBEMYKLIPH', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), JOHOR BAHRU, JOHOR', 'swift_code' => 'MBBEMYKLJOB', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), KOTA KINABALU, SABAH', 'swift_code' => 'MBBEMYKLKIN', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), KUALA LUMPUR (BANGSAR BARU BRANCH)', 'swift_code' => 'MBBEMYKLBAN', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), KUALA LUMPUR (BUKIT BINTANG BRANCH)', 'swift_code' => 'MBBEMYKLBBG', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), KUALA LUMPUR (CASH MANAGEMENT DEPARTMENT)', 'swift_code' => 'MBBEMYKLWEB', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), KUALA LUMPUR (CUSTODIAN SERVICES DEPARTMENT)', 'swift_code' => 'MBBEMYKLCSD', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), KUALA LUMPUR (KEPONG BRANCH)', 'swift_code' => 'MBBEMYKLKEP', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), KUALA LUMPUR (PUDU BRANCH)', 'swift_code' => 'MBBEMYKLPUD', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), KUALA LUMPUR (SUBANG AIRPORT)', 'swift_code' => 'MBBEMYKLSUB', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), KUALA LUMPUR (TRADE FINANCE CENTER)', 'swift_code' => 'MBBEMYKLKLC', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), KUALA LUMPUR (WISMA SIME DARBY BRANCH)', 'swift_code' => 'MBBEMYKLWSD', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), MALACCA, MALACCA', 'swift_code' => 'MBBEMYKLMAL', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), PASIR GUDANG, JOHOR', 'swift_code' => 'MBBEMYKLPSG', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), PENANG, PENANG (TRADE FINANCE CENTER)', 'swift_code' => 'MBBEMYKLPGC', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), PENANG, PENANG', 'swift_code' => 'MBBEMYKLPEN', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), PETALING JAYA, SELANGOR (TRADE FINANCE CENTER)', 'swift_code' => 'MBBEMYKLPJC', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), PETALING JAYA, SELANGOR (YONG SHOOK LIN BRANCH)', 'swift_code' => 'MBBEMYKLYSL', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), PETALING JAYA, SELANGOR', 'swift_code' => 'MBBEMYKLPJY', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), PORT KLANG, SELANGOR', 'swift_code' => 'MBBEMYKLPKG', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), SEREMBAN, NEGRI SEMBILAN', 'swift_code' => 'MBBEMYKLSBN', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), SHAH ALAM, SELANGOR  (TRADE FINANCE CENTER)', 'swift_code' => 'MBBEMYKLSAC', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYAN BANKING BERHAD (MAYBANK), SHAH ALAM, SELANGOR', 'swift_code' => 'MBBEMYKLSHA', 'bank_code' => 'MBBE'],
            ['name' => 'MALAYSIA AIRLINES BERHAD', 'swift_code' => 'MAYHMY22', 'bank_code' => 'MAYH'],
            ['name' => 'MAX MONEY SDN BHD', 'swift_code' => 'MMSBMYKL', 'bank_code' => 'MMSB'],
            ['name' => 'MAYBANK INTERNATIONAL (L) LTD.', 'swift_code' => 'MBBEMYKA', 'bank_code' => 'MBBE'],
            ['name' => 'MAYBANK INTERNATIONAL LABUAN BRANCH', 'swift_code' => 'MBBEMY2L', 'bank_code' => 'MBBE'],
            ['name' => 'MAYBANK INVESTMENT BANK BERHAD', 'swift_code' => 'MBEAMYKL', 'bank_code' => 'MBEA'],
            ['name' => 'MAYBANK ISLAMIC BERHAD', 'swift_code' => 'MBISMYKL', 'bank_code' => 'MBIS'],
            ['name' => 'MEGA INTERNATIONAL COMMERCIAL BANK CO., LTD. LABUAN BRANCH', 'swift_code' => 'ICBCMY2L', 'bank_code' => 'ICBC'],
            ['name' => 'MERCEDES-BENZ MALAYSIA SDN. BHD', 'swift_code' => 'DABEMYKL', 'bank_code' => 'DABE'],
            ['name' => 'MERCEDES-BENZ SERVICES MALAYSIA SDN. BHD.', 'swift_code' => 'MBSMMYKL', 'bank_code' => 'MBSM'],
            ['name' => 'MIDDLE EAST INVESTMENT BANK LTD', 'swift_code' => 'MEIBMYKA', 'bank_code' => 'MEIB'],
            ['name' => 'MIZUHO BANK (MALAYSIA) BERHAD', 'swift_code' => 'MHCBMYKA', 'bank_code' => 'MHCB'],
            ['name' => 'MIZUHO BANK, LTD., LABUAN BRANCH', 'swift_code' => 'MHCBMY2L', 'bank_code' => 'MHCB'],
            ['name' => 'NATIONAL BANK OF ABU DHABI', 'swift_code' => 'NBADMYKL', 'bank_code' => 'NBAD'],
            ['name' => 'NATIONAL BANK OF ABU DHABI, LABUAN', 'swift_code' => 'NBADMYKLLAU', 'bank_code' => 'NBAD'],
            ['name' => 'NOMURA ASSET MANAGEMENT MALAYSIA SDN BHD', 'swift_code' => 'NOCMMYKL', 'bank_code' => 'NOCM'],
            ['name' => 'OCBC BANK (MALAYSIA) BERHAD', 'swift_code' => 'OCBCMYKL', 'bank_code' => 'OCBC'],
            ['name' => 'OCBC AL-AMIN BANK BERHAD', 'swift_code' => 'OABBMYKL', 'bank_code' => 'OABB'],
            ['name' => 'PETROLIAM NASIONAL BERHAD', 'swift_code' => 'PTROMYKL', 'bank_code' => 'PTRO'],
            ['name' => 'PETROLIAM NASIONAL BERHAD, KUALA LUMPUR (KLCC)', 'swift_code' => 'PTROMYKLFSD', 'bank_code' => 'PTRO'],
            ['name' => 'PETRONAS CARIGALI SDN BHD', 'swift_code' => 'PCGLMYKL', 'bank_code' => 'PCGL'],
            ['name' => 'PETRONAS TRADING CORPORATION SDN. BHD', 'swift_code' => 'PTRDMYKL', 'bank_code' => 'PTRD'],
            ['name' => 'PG ASIA INVESTMENT BANK LTD', 'swift_code' => 'AINEMY22', 'bank_code' => 'AINE'],
            ['name' => 'PUBLIC BANK BERHAD', 'swift_code' => 'PBBEMYKL', 'bank_code' => 'PBBE'],
            ['name' => 'PUBLIC BANK (L) LTD', 'swift_code' => 'PBLLMYKA', 'bank_code' => 'PBLL'],
            ['name' => 'PUBLIC ISLAMIC BANK BERHAD', 'swift_code' => 'PUIBMYKL', 'bank_code' => 'PUIB'],
            ['name' => 'RHB BANK BERHAD', 'swift_code' => 'RHBBMYKL', 'bank_code' => 'RHBB'],
            ['name' => 'RHB BANK (L) LTD', 'swift_code' => 'RHBBMYKA', 'bank_code' => 'RHBB'],
            ['name' => 'RHB INVESTMENT BANK BERHAD', 'swift_code' => 'OSKIMYKL', 'bank_code' => 'OSKI'],
            ['name' => 'RHB ISLAMIC BANK BERHAD', 'swift_code' => 'RHBAMYKL', 'bank_code' => 'RHBA'],
            ['name' => 'STANDARD CHARTERED BANK MALAYSIA BERHAD', 'swift_code' => 'SCBLMYKX', 'bank_code' => 'SCBL'],
            ['name' => 'STANDARD CHARTERED BANK MALAYSIA BERHAD, (LABUAN OFFSHORE BANKING UNIT)', 'swift_code' => 'SCBLMYKXLAB', 'bank_code' => 'SCBL'],
            ['name' => 'STANDARD CHARTERED SAADIQ BERHAD', 'swift_code' => 'SCSRMYKK', 'bank_code' => 'SCSR'],
            ['name' => 'SUMITOMO MITSUI BANKING CORPORATION MALAYSIA BERHAD', 'swift_code' => 'SMBCMYKL', 'bank_code' => 'SMBC'],
            ['name' => 'SUMITOMO MITSUI BANKING CORPORATION', 'swift_code' => 'SMBCMYKA', 'bank_code' => 'SMBC'],
            ['name' => 'THE BANK OF NOVA SCOTIA,', 'swift_code' => 'NOSCMY2L', 'bank_code' => 'NOSC'],
            ['name' => 'THE BANK OF TOKYO-MITSUBISHI UFJ, LTD. (LABUAN BRANCH)', 'swift_code' => 'BOTKMYKA', 'bank_code' => 'BOTK'],
            ['name' => 'THE ROYAL BANK OF SCOTLAND BERHAD ', 'swift_code' => 'ABNAMYKL', 'bank_code' => 'ABNA'],
            ['name' => 'THE ROYAL BANK OF SCOTLAND BERHAD, PENANG, PENANG', 'swift_code' => 'ABNAMYKLPNG', 'bank_code' => 'ABNA'],
            ['name' => 'THE ROYAL BANK OF SCOTLAND PLC LABUAN BRANCH', 'swift_code' => 'ABNAMY2A', 'bank_code' => 'ABNA'],
            ['name' => 'UNITED OVERSEAS BANK (MALAYSIA) BERHAD', 'swift_code' => 'UOVBMYKL', 'bank_code' => 'UOVB'],
            ['name' => 'UNITED OVERSEAS BANK (MALAYSIA) BERHAD, (CUSTODIAN AND NOMINEES DEPARTMENT)', 'swift_code' => 'UOVBMYKLCND', 'bank_code' => 'UOVB'],
            ['name' => 'UNITED OVERSEAS BANK (MALAYSIA) BERHAD', 'swift_code' => 'UOVBMY2L', 'bank_code' => 'UOVB'],
        ];

        foreach ($banks as $bank) {
            $this->assertDatabaseHas('banks', $bank);
        }
    }
}
