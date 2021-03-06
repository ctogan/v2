<?php
namespace App\Helpers;
/**
 * Created by PhpStorm.
 * User: kervinchristianata
 * Date: 2021-06-4
 * Time: 10:00
 */

class Operator {
    public static $list = [
        '51001' => 'Indosat',
        '51021' => 'Indosat',
        '51003' => 'StarOne',
        '51007' => 'Flexi',
        '51008' => 'Axis',
        '51009' => 'SmartFren',
        '51028' => 'SmartFren',
        '51010' => 'Telkomsel',
        '51020' => 'Telkomsel',
        '51011' => 'XL',
        '51027' => 'Ceria',
        '51089' => 'Tri',
        '51099' => 'Esia',
    ];

    public static $idList = [
        '51001' => 'IN',
        '51021' => 'IN',
        '51003' => 'SO',
        '51007' => 'FL',
        '51008' => 'AX',
        '51009' => 'SF',
        '51028' => 'SF',
        '51010' => 'TE',
        '51020' => 'TE',
        '51011' => 'XL',
        '51027' => 'CE',
        '51089' => 'TR',
        '51099' => 'ES',
    ];

    public static $names = [
        'IN' => 'Indosat',
        'SO' => 'StarOne',
        'FL' => 'Flexi',
        'AX' => 'Axis',
        'SF' => 'SmartFren',
        'TE' => 'Telkomsel',
        'XL' => 'XL',
        'CE' => 'Ceria',
        'HE' => 'Hepi',
        'TR' => 'Tri',
        'ES' => 'Esia',
    ];

    const prefix_FL_ES = [
        '021',
        '022',
        '0231',
        '0232',
        '0233',
        '0234',
        '024',
        '0251',
        '0252',
        '0253',
        '0254',
        '0260',
        '0261',
        '0262',
        '0263',
        '0264',
        '0265',
        '0266',
        '0267',
        '0271',
        '0272',
        '0273',
        '0274',
        '0275',
        '0276',
        '0280',
        '0281',
        '0282',
        '0283',
        '0284',
        '0285',
        '0286',
        '0287',
        '0289',
        '0291',
        '0292',
        '0293',
        '0294',
        '0295',
        '0296',
        '0297',
        '0298',
        '031',
        '0321',
        '0322',
        '0323',
        '0324',
        '0325',
        '0326',
        '0327',
        '0328',
        '0331',
        '0332',
        '0333',
        '0334',
        '0335',
        '0338',
        '0341',
        '0342',
        '0343',
        '0351',
        '0352',
        '0353',
        '0354',
        '0355',
        '0356',
        '0357',
        '0358',
        '0361',
        '0362',
        '0363',
        '0365',
        '0366',
        '0368',
        '0370',
        '0371',
        '0372',
        '0373',
        '0374',
        '0376',
        '0379',
        '0380',
        '0381',
        '0382',
        '0383',
        '0384',
        '0385',
        '0386',
        '0387',
        '0388',
        '0389',
        '0401',
        '0402',
        '0403',
        '0404',
        '0405',
        '0408',
        '0409',
        '0410',
        '0411',
        '0413',
        '0414',
        '0417',
        '0418',
        '0419',
        '0420',
        '0421',
        '0422',
        '0423',
        '0426',
        '0427',
        '0428',
        '0430',
        '0431',
        '0432',
        '0434',
        '0435',
        '0438',
        '0443',
        '0450',
        '0451',
        '0452',
        '0453',
        '0457',
        '0458',
        '0461',
        '0462',
        '0464',
        '0471',
        '0473',
        '0474',
        '0481',
        '0482',
        '0484',
        '0485',
        '0511',
        '0512',
        '0513',
        '0517',
        '0518',
        '0519',
        '0526',
        '0526',
        '0527',
        '0528',
        '0531',
        '0532',
        '0534',
        '0535',
        '0536',
        '0537',
        '0539',
        '0541',
        '0542',
        '0543',
        '0545',
        '0548',
        '0549',
        '0551',
        '0552',
        '0553',
        '0554',
        '0556',
        '0561',
        '0562',
        '0563',
        '0564',
        '0565',
        '0567',
        '0568',
        '061',
        '0620',
        '0621',
        '0622',
        '0623',
        '0624',
        '0625',
        '0626',
        '0627',
        '0627',
        '0628',
        '0629',
        '0630',
        '0631',
        '0632',
        '0633',
        '0634',
        '0635',
        '0636',
        '0639',
        '0641',
        '0642',
        '0643',
        '0644',
        '0645',
        '0646',
        '0650',
        '0651',
        '0652',
        '0653',
        '0654',
        '0655',
        '0656',
        '0657',
        '0658',
        '0659',
        '0702',
        '0711',
        '0712',
        '0713',
        '0714',
        '0715',
        '0716',
        '0717',
        '0718',
        '0719',
        '0721',
        '0722',
        '0723',
        '0724',
        '0725',
        '0726',
        '0727',
        '0728',
        '0729',
        '0730',
        '0731',
        '0732',
        '0733',
        '0734',
        '0735',
        '0736',
        '0737',
        '0739',
        '0741',
        '0742',
        '0743',
        '0744',
        '0745',
        '0746',
        '0747',
        '0748',
        '0751',
        '0752',
        '0753',
        '0754',
        '0755',
        '0756',
        '0757',
        '0759',
        '0760',
        '0761',
        '0762',
        '0763',
        '0764',
        '0765',
        '0766',
        '0767',
        '0768',
        '0769',
        '0771',
        '0772',
        '0773',
        '0776',
        '0777',
        '0778',
        '0779',
        '0901',
        '0902',
        '0910',
        '0911',
        '0913',
        '0914',
        '0916',
        '0917',
        '0918',
        '0920',
        '0921',
        '0924',
        '0929',
        '0951',
        '0956',
        '0963',
        '0966',
        '0967',
        '0969',
        '0971',
        '0975',
        '0979',
        '0981',
        '0983',
        '0984',
        '0986'
    ];

    public static $prefix = [
        'TE' => ['0811', '0812', '0813', '0821', '0822', '0823', '0851', '0852', '0853'],
        'IN' => ['0814', '0815', '0816', '0855', '0856', '0857', '0858'],
        'XL' => ['0817', '0818', '0819', '0831', '0832', '0836', '0837', '0838', '0859', '0877', '0878', '0879'],
        'TR' => ['0890', '0891', '0892', '0893', '0894', '0895', '0896', '0897', '0898', '0899'],
        'AX' => ['0831', '0832', '0836', '0837', '0838'],
        'SF' => ['0881', '0882', '0883', '0884', '0885', '0886', '0887', '0888', '0889'],
        'ES' => Operator::prefix_FL_ES,
        'FL' => Operator::prefix_FL_ES,
    ];

    public static function name($id) {
        return static::$names[$id] ? static::$names[$id] : $id;
    }

    public static function nameByOpcode($opcode) {
        $id = static::$idList[$opcode];
        $name = static::$names[$id];
        return $name ? $name : ($id ? $id : $opcode);
    }

    public static function nameByPrefix($prefix) {
        foreach (static::$prefix as $k => $v) {
            if (array_search($prefix, $v) !== false) {
                return static::$names[$k];
            }
        }
        return $prefix;
    }

    public static function getNameByOpcode($opcode) {
        $list = [
            '51001' => 'Indosat',
            '51021' => 'Indosat',
            '51003' => 'StarOne',
            '51007' => 'Flexi',
            '51008' => 'Axis',
            '51009' => 'SmartFren',
            '51028' => 'SmartFren',
            '51010' => 'Telkomsel',
            '51020' => 'Telkomsel',
            '51011' => 'XL',
            '51027' => 'Ceria',
            '51089' => 'Tri',
            '51099' => 'Esia',
        ];
        $name = $list[$opcode];

        return $name ? $name : null;
    }

    //@throws Exception
    public static function Validation($sim_no, $op_code) {
        if (!static::SIMVerify($sim_no, $op_code)) {
            throw new Exception(gt('rooting_s_unable_body'));
        }

        if (!isset(static::$idList[$op_code])) {
            if (substr($op_code, 0, 3) == '999') {
                return 'XX';
            }
            throw new Exception(gt('Error op: '.$op_code));
        }

        return static::$idList[$op_code];
    }

    public static function SIMIsWhiteList($sim_no) {
        static $sim_white_dep;
        if (!$sim_white_dep) {
            $sim_white_dep = preg_split('/[\s,]+/', trim(Options::val('sim_white_dep')));
        }

        if ($sim_white_dep) {
            if (array_search($sim_no, $sim_white_dep) !== false) {
                return true;
            }
        }
        return false;
    }

    public static function SIMVerify($sim_no, $op_code) {
        static $sim_white_verify;
        if (!$sim_white_verify) {
            $sim_white_verify = preg_split('/[\s,]+/', trim(Options::val('sim_white_verify')));
        }

        if ($sim_white_verify) {
            if (array_search($sim_no, $sim_white_verify) !== false) {
                return true;
            }
        }

        //sim 번호 첫 4자리가, 인니sim 또는 한국sim 이 아니면, 에러
        if (substr($sim_no, 2, 2) !== '62') {
            return false;
        } // sim번호 5~6번째 자리가, op_code의 4~5번째가 다르면, 에러
        else if (substr($sim_no, 4, 2) !== substr($op_code, 3, 2)) {
            return false;
        }

        return true;
    }
}