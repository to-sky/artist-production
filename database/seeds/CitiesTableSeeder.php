<?php

use App\Models\City;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dirtyCities = array (
            0 =>
                array (
                    'id' => '10003',
                    'name' => 'Berlin',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            1 =>
                array (
                    'id' => '10004',
                    'name' => 'Bielefeld',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            2 =>
                array (
                    'id' => '10005',
                    'name' => 'Düsseldorf',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            3 =>
                array (
                    'id' => '10006',
                    'name' => 'Hamburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            4 =>
                array (
                    'id' => '176477',
                    'name' => 'Frankfurt am Main',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            5 =>
                array (
                    'id' => '184265',
                    'name' => 'Offenbach (Frankfurt am Main)',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            6 =>
                array (
                    'id' => '184266',
                    'name' => 'Kassel',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            7 =>
                array (
                    'id' => '184267',
                    'name' => 'Saarbrücken',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            8 =>
                array (
                    'id' => '184268',
                    'name' => 'Augsburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            9 =>
                array (
                    'id' => '184269',
                    'name' => 'Offenburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            10 =>
                array (
                    'id' => '184270',
                    'name' => 'Würzburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            11 =>
                array (
                    'id' => '184271',
                    'name' => 'Amsterdam',
                    'countryName' => 'Netherlands',
                    'countryId' => '3128107',
                    'latitude' => '',
                    'longitude' => '',
                ),
            12 =>
                array (
                    'id' => '184272',
                    'name' => 'Aachen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            13 =>
                array (
                    'id' => '184273',
                    'name' => 'Ratingen bei Düsseldorf',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            14 =>
                array (
                    'id' => '184274',
                    'name' => 'Siegburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            15 =>
                array (
                    'id' => '184275',
                    'name' => 'Siegen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            16 =>
                array (
                    'id' => '184276',
                    'name' => 'Wolfsburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            17 =>
                array (
                    'id' => '250148',
                    'name' => 'Nürnberg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            18 =>
                array (
                    'id' => '440162',
                    'name' => 'München',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            19 =>
                array (
                    'id' => '484006',
                    'name' => 'Esslingen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            20 =>
                array (
                    'id' => '484076',
                    'name' => 'Friedrichshafen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            21 =>
                array (
                    'id' => '568557',
                    'name' => 'Hannover',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            22 =>
                array (
                    'id' => '622942',
                    'name' => 'Altötting',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            23 =>
                array (
                    'id' => '622943',
                    'name' => 'Bayreuth',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            24 =>
                array (
                    'id' => '622944',
                    'name' => 'Bonn',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            25 =>
                array (
                    'id' => '622945',
                    'name' => 'Braunschweig',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            26 =>
                array (
                    'id' => '622946',
                    'name' => 'Bremen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            27 =>
                array (
                    'id' => '622947',
                    'name' => 'Dortmund',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            28 =>
                array (
                    'id' => '622948',
                    'name' => 'Duisburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            29 =>
                array (
                    'id' => '622949',
                    'name' => 'Essen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            30 =>
                array (
                    'id' => '622950',
                    'name' => 'Flensburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            31 =>
                array (
                    'id' => '622951',
                    'name' => 'Freiburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            32 =>
                array (
                    'id' => '622952',
                    'name' => 'Fürth',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            33 =>
                array (
                    'id' => '622953',
                    'name' => 'Gießen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            34 =>
                array (
                    'id' => '622954',
                    'name' => 'Göttingen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            35 =>
                array (
                    'id' => '622955',
                    'name' => 'Gütersloh',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            36 =>
                array (
                    'id' => '622976',
                    'name' => 'Hof',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            37 =>
                array (
                    'id' => '622977',
                    'name' => 'Ibbenbüren',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            38 =>
                array (
                    'id' => '622978',
                    'name' => 'Ingolstadt',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            39 =>
                array (
                    'id' => '622979',
                    'name' => 'Kaiserslautern',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            40 =>
                array (
                    'id' => '622980',
                    'name' => 'Karlsruhe',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            41 =>
                array (
                    'id' => '622981',
                    'name' => 'Koblenz',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            42 =>
                array (
                    'id' => '622982',
                    'name' => 'Künzell bei Fulda',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            43 =>
                array (
                    'id' => '622983',
                    'name' => 'Leinfelden bei Stuttgart',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            44 =>
                array (
                    'id' => '622984',
                    'name' => 'Leverkusen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            45 =>
                array (
                    'id' => '622985',
                    'name' => 'Lingen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            46 =>
                array (
                    'id' => '622986',
                    'name' => 'Ludwigsburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            47 =>
                array (
                    'id' => '622999',
                    'name' => 'Mannheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            48 =>
                array (
                    'id' => '623000',
                    'name' => 'Mülheim an der Ruhr',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            49 =>
                array (
                    'id' => '623001',
                    'name' => 'Neu-Ulm',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            50 =>
                array (
                    'id' => '623002',
                    'name' => 'Neuss',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            51 =>
                array (
                    'id' => '623003',
                    'name' => 'Osnabrück',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            52 =>
                array (
                    'id' => '623004',
                    'name' => 'Pforzheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            53 =>
                array (
                    'id' => '623005',
                    'name' => 'Schweinfurt',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            54 =>
                array (
                    'id' => '623006',
                    'name' => 'Stuttgart',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            55 =>
                array (
                    'id' => '623007',
                    'name' => 'Tuttlingen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            56 =>
                array (
                    'id' => '623008',
                    'name' => 'Verden',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            57 =>
                array (
                    'id' => '623009',
                    'name' => 'Wilhelmshaven',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            58 =>
                array (
                    'id' => '623010',
                    'name' => 'Trier',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            59 =>
                array (
                    'id' => '1563163',
                    'name' => 'Köln',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            60 =>
                array (
                    'id' => '1736596',
                    'name' => 'Münster',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            61 =>
                array (
                    'id' => '3246303',
                    'name' => 'Oldenburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            62 =>
                array (
                    'id' => '3533812',
                    'name' => 'Praha',
                    'countryName' => 'Czech Republic',
                    'countryId' => '3128048',
                    'latitude' => '',
                    'longitude' => '',
                ),
            63 =>
                array (
                    'id' => '3557828',
                    'name' => 'Paris',
                    'countryName' => 'France',
                    'countryId' => '3128057',
                    'latitude' => '',
                    'longitude' => '',
                ),
            64 =>
                array (
                    'id' => '3728604',
                    'name' => 'Wien',
                    'countryName' => 'Austria',
                    'countryId' => '484077',
                    'latitude' => '',
                    'longitude' => '',
                ),
            65 =>
                array (
                    'id' => '3847869',
                    'name' => 'Zürich',
                    'countryName' => 'Switzerland',
                    'countryId' => '3128164',
                    'latitude' => '',
                    'longitude' => '',
                ),
            66 =>
                array (
                    'id' => '4177447',
                    'name' => 'London',
                    'countryName' => 'United Kingdom',
                    'countryId' => '3128182',
                    'latitude' => '',
                    'longitude' => '',
                ),
            67 =>
                array (
                    'id' => '4325404',
                    'name' => 'Mainz',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            68 =>
                array (
                    'id' => '4341949',
                    'name' => 'Salzburg',
                    'countryName' => 'Austria',
                    'countryId' => '484077',
                    'latitude' => '',
                    'longitude' => '',
                ),
            69 =>
                array (
                    'id' => '4341961',
                    'name' => 'Emden',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            70 =>
                array (
                    'id' => '4341983',
                    'name' => 'Andernach',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            71 =>
                array (
                    'id' => '4342303',
                    'name' => 'Heilbronn',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            72 =>
                array (
                    'id' => '4342433',
                    'name' => 'Kirchheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            73 =>
                array (
                    'id' => '4342462',
                    'name' => 'Regensburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            74 =>
                array (
                    'id' => '4342465',
                    'name' => 'Memmingen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            75 =>
                array (
                    'id' => '4342476',
                    'name' => 'Trossingen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            76 =>
                array (
                    'id' => '4342498',
                    'name' => 'Baunatal',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            77 =>
                array (
                    'id' => '4345159',
                    'name' => 'Kulmbach',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            78 =>
                array (
                    'id' => '4345164',
                    'name' => 'Homburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            79 =>
                array (
                    'id' => '4345165',
                    'name' => 'Delbrück',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            80 =>
                array (
                    'id' => '4345170',
                    'name' => 'Neumünster',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            81 =>
                array (
                    'id' => '4345172',
                    'name' => 'Unna',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            82 =>
                array (
                    'id' => '4345174',
                    'name' => 'Sigmaringen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            83 =>
                array (
                    'id' => '4345178',
                    'name' => 'Olpe',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            84 =>
                array (
                    'id' => '4345180',
                    'name' => 'Bamberg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            85 =>
                array (
                    'id' => '4345377',
                    'name' => 'Gummersbach',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            86 =>
                array (
                    'id' => '4345387',
                    'name' => 'Erkrath',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            87 =>
                array (
                    'id' => '4345418',
                    'name' => 'Paderborn',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            88 =>
                array (
                    'id' => '4345459',
                    'name' => 'Aschaffenburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            89 =>
                array (
                    'id' => '4345460',
                    'name' => 'Amberg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            90 =>
                array (
                    'id' => '4345486',
                    'name' => 'Leinfelden-Echterdingen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            91 =>
                array (
                    'id' => '4345533',
                    'name' => 'Limburg an der Lahn',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            92 =>
                array (
                    'id' => '4364307',
                    'name' => 'Lahr',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            93 =>
                array (
                    'id' => '4364310',
                    'name' => 'Zweibrücken',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            94 =>
                array (
                    'id' => '4364316',
                    'name' => 'Mettmann',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            95 =>
                array (
                    'id' => '4364318',
                    'name' => 'Bad Godesberg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            96 =>
                array (
                    'id' => '4389145',
                    'name' => 'Baden-Baden',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            97 =>
                array (
                    'id' => '4545852',
                    'name' => 'Coesfeld',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            98 =>
                array (
                    'id' => '4908478',
                    'name' => 'Vechta',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            99 =>
                array (
                    'id' => '4908614',
                    'name' => 'Traunreut',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            100 =>
                array (
                    'id' => '4908778',
                    'name' => 'Weiden',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            101 =>
                array (
                    'id' => '4908956',
                    'name' => 'Kleve',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            102 =>
                array (
                    'id' => '4909023',
                    'name' => 'Coburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            103 =>
                array (
                    'id' => '4931634',
                    'name' => 'Bad Kreuznach',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            104 =>
                array (
                    'id' => '5895849',
                    'name' => 'Wuppertal',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            105 =>
                array (
                    'id' => '6161543',
                    'name' => 'Cloppenburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            106 =>
                array (
                    'id' => '6189173',
                    'name' => 'Flörsheim am Main',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            107 =>
                array (
                    'id' => '6339228',
                    'name' => 'Dresden',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            108 =>
                array (
                    'id' => '6346168',
                    'name' => 'Heidelberg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            109 =>
                array (
                    'id' => '6349821',
                    'name' => 'Freiburg im Breisgau',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            110 =>
                array (
                    'id' => '6363496',
                    'name' => 'Hamm',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            111 =>
                array (
                    'id' => '6406145',
                    'name' => 'Stade (bei Hamburg)',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            112 =>
                array (
                    'id' => '6501293',
                    'name' => 'Bünde',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            113 =>
                array (
                    'id' => '6514998',
                    'name' => 'Bühne',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            114 =>
                array (
                    'id' => '6515700',
                    'name' => 'Leipzig',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            115 =>
                array (
                    'id' => '6523973',
                    'name' => 'Rastatt',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            116 =>
                array (
                    'id' => '6538885',
                    'name' => 'Waiblingen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            117 =>
                array (
                    'id' => '6550425',
                    'name' => 'Idar Oberstein',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            118 =>
                array (
                    'id' => '10035302',
                    'name' => 'Leer',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            119 =>
                array (
                    'id' => '10335631',
                    'name' => 'Garching bei München',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            120 =>
                array (
                    'id' => '10482576',
                    'name' => 'Lemgo',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            121 =>
                array (
                    'id' => '12383703',
                    'name' => 'Hilden',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            122 =>
                array (
                    'id' => '12399559',
                    'name' => 'Neu-Isenburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            123 =>
                array (
                    'id' => '16669506',
                    'name' => 'Magdeburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            124 =>
                array (
                    'id' => '16689260',
                    'name' => 'Euskirchen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            125 =>
                array (
                    'id' => '16713865',
                    'name' => 'Freital',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            126 =>
                array (
                    'id' => '16760780',
                    'name' => 'Mönchengladbach',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            127 =>
                array (
                    'id' => '17075225',
                    'name' => 'Allendorf',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '',
                    'longitude' => '',
                ),
            128 =>
                array (
                    'id' => '30778115',
                    'name' => 'Antwerpen',
                    'countryName' => 'Belgium',
                    'countryId' => '3128026',
                    'latitude' => '',
                    'longitude' => '',
                ),
            129 =>
                array (
                    'id' => '52623227',
                    'name' => 'Ravensburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '47,77827',
                    'longitude' => '9,61213',
                ),
            130 =>
                array (
                    'id' => '64186163',
                    'name' => 'Deggendorf',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            131 =>
                array (
                    'id' => '64186801',
                    'name' => 'Schwerin',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            132 =>
                array (
                    'id' => '67245226',
                    'name' => 'Reichenbach',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            133 =>
                array (
                    'id' => '67245389',
                    'name' => 'Erding',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            134 =>
                array (
                    'id' => '70120350',
                    'name' => 'Lahnstein bei Koblenz',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            135 =>
                array (
                    'id' => '77150860',
                    'name' => 'Saint Petersburg',
                    'countryName' => 'Russia',
                    'countryId' => '3128207',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            136 =>
                array (
                    'id' => '77150929',
                    'name' => 'Moscow',
                    'countryName' => 'Russia',
                    'countryId' => '3128207',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            137 =>
                array (
                    'id' => '101236494',
                    'name' => 'Lübbecke',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '8,62231',
                    'longitude' => '8,62231',
                ),
            138 =>
                array (
                    'id' => '103341027',
                    'name' => 'Düren',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            139 =>
                array (
                    'id' => '105483207',
                    'name' => 'Werl',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            140 =>
                array (
                    'id' => '105496862',
                    'name' => 'Nienburg (Weser)',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            141 =>
                array (
                    'id' => '114241487',
                    'name' => 'Göppingen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            142 =>
                array (
                    'id' => '115686594',
                    'name' => 'Neuwied',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            143 =>
                array (
                    'id' => '126324494',
                    'name' => 'Rheine',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            144 =>
                array (
                    'id' => '126327719',
                    'name' => 'Schwabach',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            145 =>
                array (
                    'id' => '126331386',
                    'name' => 'Wetzlar',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            146 =>
                array (
                    'id' => '139390632',
                    'name' => 'Sankt Augustin',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            147 =>
                array (
                    'id' => '142083724',
                    'name' => 'Kiel',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            148 =>
                array (
                    'id' => '143581092',
                    'name' => 'Troisdorf',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            149 =>
                array (
                    'id' => '143581205',
                    'name' => 'Northeim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            150 =>
                array (
                    'id' => '143587535',
                    'name' => 'Hanau',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            151 =>
                array (
                    'id' => '143593052',
                    'name' => 'Weingarten',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            152 =>
                array (
                    'id' => '143604682',
                    'name' => 'Solingen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            153 =>
                array (
                    'id' => '143604762',
                    'name' => 'Wiesloch',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            154 =>
                array (
                    'id' => '143616359',
                    'name' => 'Öhringen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            155 =>
                array (
                    'id' => '143623188',
                    'name' => 'Fürstenfeldbruck',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            156 =>
                array (
                    'id' => '143626271',
                    'name' => 'Oberursel',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            157 =>
                array (
                    'id' => '143629868',
                    'name' => 'Bad Nauheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            158 =>
                array (
                    'id' => '143629938',
                    'name' => 'Bad Homburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            159 =>
                array (
                    'id' => '143631054',
                    'name' => 'Neustadt a. d. W.',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            160 =>
                array (
                    'id' => '143634049',
                    'name' => 'Lünen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            161 =>
                array (
                    'id' => '143635776',
                    'name' => 'Celle',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            162 =>
                array (
                    'id' => '143635839',
                    'name' => 'Marl',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            163 =>
                array (
                    'id' => '143636189',
                    'name' => 'Herne',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            164 =>
                array (
                    'id' => '143637315',
                    'name' => 'Bad Orb',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            165 =>
                array (
                    'id' => '143638167',
                    'name' => 'Cuxhaven',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            166 =>
                array (
                    'id' => '146352475',
                    'name' => 'Madrid',
                    'countryName' => 'Spain',
                    'countryId' => '3128155',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            167 =>
                array (
                    'id' => '146352498',
                    'name' => 'Marbella',
                    'countryName' => 'Spain',
                    'countryId' => '3128155',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            168 =>
                array (
                    'id' => '146352501',
                    'name' => 'Benidorm',
                    'countryName' => 'Spain',
                    'countryId' => '3128155',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            169 =>
                array (
                    'id' => '146352502',
                    'name' => 'Valencia',
                    'countryName' => 'Spain',
                    'countryId' => '3128155',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            170 =>
                array (
                    'id' => '146352503',
                    'name' => 'Tenerife',
                    'countryName' => 'Spain',
                    'countryId' => '3128155',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            171 =>
                array (
                    'id' => '146352504',
                    'name' => 'Barcelona',
                    'countryName' => 'Spain',
                    'countryId' => '3128155',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            172 =>
                array (
                    'id' => '146352507',
                    'name' => 'Tarragona',
                    'countryName' => 'Spain',
                    'countryId' => '3128155',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            173 =>
                array (
                    'id' => '146352543',
                    'name' => 'Nice',
                    'countryName' => 'France',
                    'countryId' => '3128057',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            174 =>
                array (
                    'id' => '148135784',
                    'name' => 'Emmerich am Rhein',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            175 =>
                array (
                    'id' => '151437009',
                    'name' => 'Mosbach',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            176 =>
                array (
                    'id' => '151447524',
                    'name' => 'Wolfratshausen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            177 =>
                array (
                    'id' => '151447899',
                    'name' => 'Bad Reichenhall',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            178 =>
                array (
                    'id' => '151448207',
                    'name' => 'Papenburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            179 =>
                array (
                    'id' => '151449333',
                    'name' => 'Landau in der Pfalz',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            180 =>
                array (
                    'id' => '151449591',
                    'name' => 'Ansbach',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            181 =>
                array (
                    'id' => '151451630',
                    'name' => 'Donaueschingen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            182 =>
                array (
                    'id' => '151451631',
                    'name' => 'Suhl',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            183 =>
                array (
                    'id' => '151451634',
                    'name' => 'Amstetten',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            184 =>
                array (
                    'id' => '151451703',
                    'name' => 'Calw',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            185 =>
                array (
                    'id' => '153553288',
                    'name' => 'Neunburg vorm Wald',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            186 =>
                array (
                    'id' => '153553329',
                    'name' => 'Pfullendorf',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            187 =>
                array (
                    'id' => '153553359',
                    'name' => 'Borken',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            188 =>
                array (
                    'id' => '153553412',
                    'name' => 'Osterode am Harz',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            189 =>
                array (
                    'id' => '153553437',
                    'name' => 'Wernau',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            190 =>
                array (
                    'id' => '153559386',
                    'name' => 'Bruchsal',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            191 =>
                array (
                    'id' => '153559405',
                    'name' => 'Homberg (Efze)',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            192 =>
                array (
                    'id' => '153559412',
                    'name' => 'Günzburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            193 =>
                array (
                    'id' => '153559417',
                    'name' => 'Baiersbronn',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            194 =>
                array (
                    'id' => '153559694',
                    'name' => 'Bühl',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            195 =>
                array (
                    'id' => '153560722',
                    'name' => 'Böblingen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            196 =>
                array (
                    'id' => '153561561',
                    'name' => 'Schwäbisch Gmund',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            197 =>
                array (
                    'id' => '153561941',
                    'name' => 'Bremerhaven',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            198 =>
                array (
                    'id' => '153571994',
                    'name' => 'Hagen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            199 =>
                array (
                    'id' => '153591193',
                    'name' => 'Halle (Saale)',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            200 =>
                array (
                    'id' => '156747337',
                    'name' => 'Essenbach/Landshut',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            201 =>
                array (
                    'id' => '156747351',
                    'name' => 'Straubing',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            202 =>
                array (
                    'id' => '158036452',
                    'name' => 'Monaco',
                    'countryName' => 'Monaco',
                    'countryId' => '3128098',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            203 =>
                array (
                    'id' => '161174566',
                    'name' => 'Kreuzlingen',
                    'countryName' => 'Switzerland',
                    'countryId' => '3128164',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            204 =>
                array (
                    'id' => '161584716',
                    'name' => 'Elsteraue',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            205 =>
                array (
                    'id' => '161584824',
                    'name' => 'Lohr am Main',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            206 =>
                array (
                    'id' => '162892146',
                    'name' => 'Bochum',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            207 =>
                array (
                    'id' => '163436892',
                    'name' => 'Ulm',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            208 =>
                array (
                    'id' => '163437106',
                    'name' => 'Erfurt',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            209 =>
                array (
                    'id' => '163437136',
                    'name' => 'Attendorn',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            210 =>
                array (
                    'id' => '163438043',
                    'name' => 'Villingen-Schwenningen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            211 =>
                array (
                    'id' => '163438969',
                    'name' => 'Stendal',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            212 =>
                array (
                    'id' => '163439050',
                    'name' => 'Neuruppin',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            213 =>
                array (
                    'id' => '175495855',
                    'name' => 'Fribourg',
                    'countryName' => 'Switzerland',
                    'countryId' => '3128164',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            214 =>
                array (
                    'id' => '195817504',
                    'name' => 'Hameln',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            215 =>
                array (
                    'id' => '195817654',
                    'name' => 'Veitshöchheim (Würzburg)',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            216 =>
                array (
                    'id' => '198275317',
                    'name' => 'Geldern',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            217 =>
                array (
                    'id' => '198275752',
                    'name' => 'Hildesheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            218 =>
                array (
                    'id' => '199674043',
                    'name' => 'Leimen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            219 =>
                array (
                    'id' => '201042555',
                    'name' => 'Ludwigshafen am Rhein',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            220 =>
                array (
                    'id' => '201054122',
                    'name' => 'Heidenheim an der Brenz',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            221 =>
                array (
                    'id' => '201059221',
                    'name' => 'Denzlingen (Freiburg)',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            222 =>
                array (
                    'id' => '201701615',
                    'name' => 'Dubai',
                    'countryName' => 'United Arab Emirates',
                    'countryId' => '3128181',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            223 =>
                array (
                    'id' => '202694923',
                    'name' => 'Isernhagen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            224 =>
                array (
                    'id' => '209121450',
                    'name' => 'Dingolfing',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            225 =>
                array (
                    'id' => '209121649',
                    'name' => 'Passau',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            226 =>
                array (
                    'id' => '209121782',
                    'name' => 'Aalen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            227 =>
                array (
                    'id' => '209121981',
                    'name' => 'Freudenstadt',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            228 =>
                array (
                    'id' => '209485101',
                    'name' => 'Belm',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            229 =>
                array (
                    'id' => '210215865',
                    'name' => 'Nürtingen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            230 =>
                array (
                    'id' => '210229314',
                    'name' => 'Pocking',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            231 =>
                array (
                    'id' => '210247365',
                    'name' => 'Kornwestheim (Stuttgart)',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            232 =>
                array (
                    'id' => '211219843',
                    'name' => 'Hoerstel',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            233 =>
                array (
                    'id' => '211591823',
                    'name' => 'Langenhagen (Hannover)',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            234 =>
                array (
                    'id' => '211612574',
                    'name' => 'Schorndorf',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            235 =>
                array (
                    'id' => '211626360',
                    'name' => 'Weinheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            236 =>
                array (
                    'id' => '211626363',
                    'name' => 'Kirchheimbolanden',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            237 =>
                array (
                    'id' => '211626387',
                    'name' => 'Kaufbeuren',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            238 =>
                array (
                    'id' => '211626398',
                    'name' => 'Witten',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            239 =>
                array (
                    'id' => '212725519',
                    'name' => 'Arnsberg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            240 =>
                array (
                    'id' => '214547330',
                    'name' => 'Darmstadt',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            241 =>
                array (
                    'id' => '217131796',
                    'name' => 'Obertshausen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            242 =>
                array (
                    'id' => '218095925',
                    'name' => 'Ingelheim am Rhein',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            243 =>
                array (
                    'id' => '219762743',
                    'name' => 'Salzgitter',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            244 =>
                array (
                    'id' => '223050477',
                    'name' => 'Alicante',
                    'countryName' => 'Spain',
                    'countryId' => '3128155',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            245 =>
                array (
                    'id' => '234909636',
                    'name' => 'Helsinki',
                    'countryName' => 'Finland',
                    'countryId' => '3128056',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            246 =>
                array (
                    'id' => '238558709',
                    'name' => 'Freilassing',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            247 =>
                array (
                    'id' => '238678522',
                    'name' => 'Salzwedel',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            248 =>
                array (
                    'id' => '239033497',
                    'name' => 'Fulda',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            249 =>
                array (
                    'id' => '239135766',
                    'name' => 'Westerstede',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            250 =>
                array (
                    'id' => '240836504',
                    'name' => 'Biberach',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            251 =>
                array (
                    'id' => '240926380',
                    'name' => 'Oer-Erkenschwick',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            252 =>
                array (
                    'id' => '241409997',
                    'name' => 'Iserlohn',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            253 =>
                array (
                    'id' => '241815897',
                    'name' => 'Senden',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            254 =>
                array (
                    'id' => '244057734',
                    'name' => 'Backnang',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            255 =>
                array (
                    'id' => '245023230',
                    'name' => 'Taufkirchen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            256 =>
                array (
                    'id' => '245023273',
                    'name' => 'Wattenscheid',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            257 =>
                array (
                    'id' => '245023990',
                    'name' => 'Dillingen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            258 =>
                array (
                    'id' => '247447608',
                    'name' => 'Konstanz',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            259 =>
                array (
                    'id' => '251984610',
                    'name' => 'Rüsselsheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            260 =>
                array (
                    'id' => '253232338',
                    'name' => 'Crailsheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            261 =>
                array (
                    'id' => '253234374',
                    'name' => 'Oerlinghausen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            262 =>
                array (
                    'id' => '259122872',
                    'name' => 'Detmold',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            263 =>
                array (
                    'id' => '259122882',
                    'name' => 'Krefeld',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            264 =>
                array (
                    'id' => '259122888',
                    'name' => 'Altenkirchen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            265 =>
                array (
                    'id' => '259122961',
                    'name' => 'Herford',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            266 =>
                array (
                    'id' => '259123269',
                    'name' => 'Singen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            267 =>
                array (
                    'id' => '270450380',
                    'name' => 'Dublin',
                    'countryName' => 'Ireland',
                    'countryId' => '3128071',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            268 =>
                array (
                    'id' => '272363945',
                    'name' => 'Alzey-Kirchheimbolanden',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            269 =>
                array (
                    'id' => '276852434',
                    'name' => 'Bad Salzuflen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            270 =>
                array (
                    'id' => '280181266',
                    'name' => 'Wiesbaden',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            271 =>
                array (
                    'id' => '283589572',
                    'name' => 'Rheinbach',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            272 =>
                array (
                    'id' => '289059982',
                    'name' => 'Mühlheim am Main',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            273 =>
                array (
                    'id' => '294411969',
                    'name' => 'Recklinghausen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '51,6167',
                    'longitude' => '7,2',
                ),
            274 =>
                array (
                    'id' => '295434894',
                    'name' => 'Erlangen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '49,3527',
                    'longitude' => '11,0028',
                ),
            275 =>
                array (
                    'id' => '295915681',
                    'name' => 'Neunkirchen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            276 =>
                array (
                    'id' => '301303391',
                    'name' => 'Bergheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            277 =>
                array (
                    'id' => '307361627',
                    'name' => 'Bregenz',
                    'countryName' => 'Austria',
                    'countryId' => '484077',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            278 =>
                array (
                    'id' => '307665925',
                    'name' => 'Füssen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            279 =>
                array (
                    'id' => '307720856',
                    'name' => 'Marburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            280 =>
                array (
                    'id' => '307771342',
                    'name' => 'Innsbruck',
                    'countryName' => 'Austria',
                    'countryId' => '484077',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            281 =>
                array (
                    'id' => '309846046',
                    'name' => 'Luxemburg',
                    'countryName' => 'Luxembourg',
                    'countryId' => '3128082',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            282 =>
                array (
                    'id' => '309920889',
                    'name' => 'Garmisch-Partenkirchen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            283 =>
                array (
                    'id' => '310366037',
                    'name' => 'Halkidiki',
                    'countryName' => 'Greece',
                    'countryId' => '3128060',
                    'latitude' => '40,3695',
                    'longitude' => '23,28708',
                ),
            284 =>
                array (
                    'id' => '310390076',
                    'name' => 'Hardheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            285 =>
                array (
                    'id' => '312901435',
                    'name' => 'Landshut',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            286 =>
                array (
                    'id' => '313654232',
                    'name' => 'Hockenheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            287 =>
                array (
                    'id' => '313738377',
                    'name' => 'Bad Hersfeld',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            288 =>
                array (
                    'id' => '313849544',
                    'name' => 'Fellbach/Stuttgart',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            289 =>
                array (
                    'id' => '314127992',
                    'name' => 'Gera',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            290 =>
                array (
                    'id' => '314145446',
                    'name' => 'Dresden',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            291 =>
                array (
                    'id' => '315853852',
                    'name' => 'Hofheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            292 =>
                array (
                    'id' => '315860702',
                    'name' => 'Alsfeld',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            293 =>
                array (
                    'id' => '315865234',
                    'name' => 'Nagold',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            294 =>
                array (
                    'id' => '315873166',
                    'name' => 'Friesenheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            295 =>
                array (
                    'id' => '315939413',
                    'name' => 'Wesel',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            296 =>
                array (
                    'id' => '315951515',
                    'name' => 'Heide',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            297 =>
                array (
                    'id' => '315960260',
                    'name' => 'Eckernförde',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            298 =>
                array (
                    'id' => '316440803',
                    'name' => 'Schwäbisch Hall',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            299 =>
                array (
                    'id' => '316447605',
                    'name' => 'Blaufelden',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            300 =>
                array (
                    'id' => '316495931',
                    'name' => 'Rostock',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            301 =>
                array (
                    'id' => '316853746',
                    'name' => 'Frankenthal',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            302 =>
                array (
                    'id' => '319237354',
                    'name' => 'Aurich',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            303 =>
                array (
                    'id' => '322926453',
                    'name' => 'Cottbus',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            304 =>
                array (
                    'id' => '322950446',
                    'name' => 'Weimar',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            305 =>
                array (
                    'id' => '322969900',
                    'name' => 'Lübeck',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            306 =>
                array (
                    'id' => '333712488',
                    'name' => 'Bramsche',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            307 =>
                array (
                    'id' => '344093776',
                    'name' => 'Bergisch Gladbach',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            308 =>
                array (
                    'id' => '345845827',
                    'name' => 'Basel',
                    'countryName' => 'Switzerland',
                    'countryId' => '3128164',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            309 =>
                array (
                    'id' => '353158013',
                    'name' => 'Hochheim am Main',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            310 =>
                array (
                    'id' => '355913292',
                    'name' => 'Uelzen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            311 =>
                array (
                    'id' => '355920392',
                    'name' => 'Horn-Bad Meinberg (Detmold)',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            312 =>
                array (
                    'id' => '355931826',
                    'name' => 'Hitzacker/Elbe',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            313 =>
                array (
                    'id' => '356248416',
                    'name' => 'Kamen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            314 =>
                array (
                    'id' => '362974109',
                    'name' => 'Husum',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            315 =>
                array (
                    'id' => '364870248',
                    'name' => 'Tauberbischofsheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            316 =>
                array (
                    'id' => '364872641',
                    'name' => 'Friesoythe',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            317 =>
                array (
                    'id' => '365014852',
                    'name' => 'Höxter',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            318 =>
                array (
                    'id' => '365053888',
                    'name' => 'Speyer',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            319 =>
                array (
                    'id' => '366812702',
                    'name' => 'Vallendar (bei Koblenz)',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            320 =>
                array (
                    'id' => '372653144',
                    'name' => 'Elversberg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            321 =>
                array (
                    'id' => '372655299',
                    'name' => 'Heilbad Heiligenstadt',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            322 =>
                array (
                    'id' => '375985625',
                    'name' => 'Milano',
                    'countryName' => 'Italy',
                    'countryId' => '3128073',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            323 =>
                array (
                    'id' => '377658358',
                    'name' => 'Den Haag',
                    'countryName' => 'Netherlands',
                    'countryId' => '3128107',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            324 =>
                array (
                    'id' => '378323475',
                    'name' => 'Hattersheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            325 =>
                array (
                    'id' => '378330823',
                    'name' => 'Heuchelheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            326 =>
                array (
                    'id' => '378771652',
                    'name' => 'Steinau',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            327 =>
                array (
                    'id' => '393069366',
                    'name' => 'Staßfurt',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            328 =>
                array (
                    'id' => '397459334',
                    'name' => 'Malmö',
                    'countryName' => 'Sweden',
                    'countryId' => '3128163',
                    'latitude' => '1',
                    'longitude' => '1',
                ),
            329 =>
                array (
                    'id' => '397465701',
                    'name' => 'Aarhus',
                    'countryName' => 'Denmark',
                    'countryId' => '3128049',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            330 =>
                array (
                    'id' => '403646725',
                    'name' => 'Garbsen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            331 =>
                array (
                    'id' => '408086398',
                    'name' => 'Ahlen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            332 =>
                array (
                    'id' => '412909445',
                    'name' => 'Bad Neustadt',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            333 =>
                array (
                    'id' => '415443626',
                    'name' => 'Beckum',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            334 =>
                array (
                    'id' => '426865930',
                    'name' => 'Chemnitz',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            335 =>
                array (
                    'id' => '436240674',
                    'name' => 'Tuttlingen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            336 =>
                array (
                    'id' => '438071852',
                    'name' => 'München/Haar',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            337 =>
                array (
                    'id' => '445581648',
                    'name' => 'Delmenhorst',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            338 =>
                array (
                    'id' => '445972510',
                    'name' => 'Stockholm',
                    'countryName' => 'Sweden',
                    'countryId' => '3128163',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            339 =>
                array (
                    'id' => '450125882',
                    'name' => 'Jūrmala',
                    'countryName' => 'Latvia',
                    'countryId' => '3128077',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            340 =>
                array (
                    'id' => '455581364',
                    'name' => 'Buchen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            341 =>
                array (
                    'id' => '455594166',
                    'name' => 'Lindau',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            342 =>
                array (
                    'id' => '455603631',
                    'name' => 'Cham',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            343 =>
                array (
                    'id' => '455624435',
                    'name' => 'Alsdorf',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            344 =>
                array (
                    'id' => '455632981',
                    'name' => 'Ilsenburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            345 =>
                array (
                    'id' => '455639608',
                    'name' => 'Weilburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            346 =>
                array (
                    'id' => '455682279',
                    'name' => 'Bad Oeynhausen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            347 =>
                array (
                    'id' => '455682646',
                    'name' => 'Landsberg am Lech',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            348 =>
                array (
                    'id' => '455744732',
                    'name' => 'Bergheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            349 =>
                array (
                    'id' => '459001949',
                    'name' => 'Reutlingen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            350 =>
                array (
                    'id' => '459005133',
                    'name' => 'Bensheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            351 =>
                array (
                    'id' => '465142560',
                    'name' => 'Espelkamp',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            352 =>
                array (
                    'id' => '468677774',
                    'name' => 'Wassenberg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            353 =>
                array (
                    'id' => '474627225',
                    'name' => 'Mühldorf am Inn',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            354 =>
                array (
                    'id' => '474635026',
                    'name' => 'Bietigheim-Bissingen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            355 =>
                array (
                    'id' => '474646562',
                    'name' => 'Langenfeld (Rheinpfalz)',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            356 =>
                array (
                    'id' => '474653061',
                    'name' => 'Lüdenscheid',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            357 =>
                array (
                    'id' => '474678979',
                    'name' => 'Gladbeck',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            358 =>
                array (
                    'id' => '474742811',
                    'name' => 'Germersheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            359 =>
                array (
                    'id' => '474754471',
                    'name' => 'Langen',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            360 =>
                array (
                    'id' => '474870737',
                    'name' => 'Stadtallendorf',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            361 =>
                array (
                    'id' => '474880528',
                    'name' => 'Bad Windsheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            362 =>
                array (
                    'id' => '475240756',
                    'name' => 'Albstadt',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            363 =>
                array (
                    'id' => '475878466',
                    'name' => 'Tallinn',
                    'countryName' => 'Estonia',
                    'countryId' => '3128055',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            364 =>
                array (
                    'id' => '475878642',
                    'name' => 'Riga',
                    'countryName' => 'Latvia',
                    'countryId' => '3128077',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            365 =>
                array (
                    'id' => '483574764',
                    'name' => 'Niedernhausen (Wiesbaden)',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            366 =>
                array (
                    'id' => '487707204',
                    'name' => 'Gronau',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            367 =>
                array (
                    'id' => '493210512',
                    'name' => 'Breisach am Rhein',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            368 =>
                array (
                    'id' => '508785238',
                    'name' => 'Rosenheim',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            369 =>
                array (
                    'id' => '517687914',
                    'name' => 'Warsaw',
                    'countryName' => 'Poland',
                    'countryId' => '3128131',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            370 =>
                array (
                    'id' => '521246576',
                    'name' => 'Meschede',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            371 =>
                array (
                    'id' => '521834862',
                    'name' => 'Rees',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            372 =>
                array (
                    'id' => '523140781',
                    'name' => 'Walldürn',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            373 =>
                array (
                    'id' => '525693511',
                    'name' => 'Soest',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            374 =>
                array (
                    'id' => '543433278',
                    'name' => 'Waldkraiburg',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            375 =>
                array (
                    'id' => '548548620',
                    'name' => 'Durlach bei Karlsruhe',
                    'countryName' => 'Germany',
                    'countryId' => '10002',
                    'latitude' => '0',
                    'longitude' => '0',
                ),
            376 =>
                array (
                    'id' => '576768891',
                    'name' => 'Copenhagen',
                    'countryName' => 'Denmark',
                    'countryId' => '3128049',
                    'latitude' => '1000',
                    'longitude' => '1000',
                ),
        );

        $cities = [];
        foreach($dirtyCities as $dirtyCity) {
            $cities[] = [
                'name' => $dirtyCity['name'],
                'countries_id' => $dirtyCity['countryId']
            ];
        }

        City::insert($cities);
    }
}
