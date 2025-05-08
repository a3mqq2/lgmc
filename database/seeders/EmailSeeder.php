<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Email;

class EmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entries = [
            '(alberta) registration@cpsa.ab.ca',
            '(cgs@mcnz.org.nz) cgs@mcnz.org.nz',
            '(أستراليا) cogs@ahpra.gov.au',
            '(cogs@mcirl.ie) cogs@mcirl.ie',
            '(Ontario) regcomm@cpso.on.ca',
            '(cpc@cpsbc.ca) cpc@cpsbc.ca',
            '(cpsnl@cpsnl.ca) cpsnl@cpsnl.ca',
            '(GJames@cpsbc.ca) GJames@cpsbc.ca',
            '(GMC) gmc@gmc-uk.org',
            '(GMCGoodSt@gmc-uk.org) GMCGoodSt@gmc-uk.org',
            '(GMCGoodStanding@gmc-uk.org) GMCGoodStanding@gmc-uk.org',
            '(Hijazi_Hussain) HHijazi@ECFMG.org',
            '(img@gmc-uk.org) img@gmc-uk.org',
            '(rory.knox@mcirl.ie) rory.knox@mcirl.ie',
            '(jkhawam@phcc.gov.qa) jkhawam@phcc.gov.qa',
            '(licensure@ecfmg.org) licensure@ecfmg.org',
            '(credentials@cpso.on.ca) credentials@cpso.on.ca',
            '(Registration@mcirl.ie) Registration@mcirl.ie',
            '(TDrover@cpsnl.ca) TDrover@cpsnl.ca',
            '(Verifications@ECFMG.org) Verifications@ECFMG.org',
            '(VERL@gmc-uk.org) VERL@gmc-uk.org',
            '(VHobeika@ECFMG.org) VHobeika@ECFMG.org',
            '(ynabahin@dataflowgroup.com) ynabahin@dataflowgroup.com',
            '(mmdc@health.gov.mv) mmdc@health.gov.mv',
            '(contact-cnom@ordre-medecins.tn) contact-cnom@ordre-medecins.tn',
            '(exercice.medica-cnom@ordre-medecins.tn) exercice.medica-cnom@ordre-medecins.tn',
            '(QCHPgoodst@moph.gov.qa) QCHPgoodst@moph.gov.qa',
            '(info@ems.org.eg) info@ems.org.eg',
            '(info@oml.org.lb) info@oml.org.lb',
        ];

        foreach ($entries as $e) {
            Email::updateOrCreate(
                ['email' => $e],
                ['email' => $e]
            );
        }
    }
}
