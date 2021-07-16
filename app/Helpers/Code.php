<?php
namespace App\Helpers;
/**
 * Created by PhpStorm.
 * User: agusttampubolon
 * Date: 2021-05-19
 * Time: 14:54
 */

class Code {

	const BIO_ENTRY_REWARD = 100;
    const TYPE_CPR = 'cpr';
    const CODE_RELEASE = 'E101';            //[적립]화면을 열기  화면을 release 하여 적립 earn
    const CODE_CPC = 'E102';                //[적립]{광고 제목} CPC 광고 적립 earn
    const CODE_CPI = 'E103';                //[적립]{광고 제목} CPI 광고 적립 earn
    const CODE_CPE = 'E104';                //[적립]{광고 제목} CPE 광고 적립 earn
    const CODE_PARTNER_PAY = 'E105';        //[적립]{광고주 적립 문구} 광고주(파트너)의 요청에 따라 유상적립 - 돈 받고 적립해주는 것임.  earn
    const CODE_CPR = 'E106';                //[유지적립]{광고 제목} 유지광고 적립 earn
    const CODE_CPV = 'E107';                //[비디오]{광고 제목} 비디오 적립 earn
    const CODE_VIRALAD = 'E108';                //[앱공유]{광고 제목} 앱 소개 earn

    //ad network
    const CODE_ADNET_ADXMI = 'E211';
    const CODE_ADNET_SUPERS = 'E212';
    const CODE_ADNET_VUNGLE = 'E213';
    const CODE_ADNET_TAPJOY = 'E214';
    const CODE_ADNET_FYBER = 'E215';
    const CODE_ADNET_VALUEPOTION = 'E216';
    const CODE_ADNET_MOBVISTA = 'E217';
    const CODE_ADNET_ADPOPCORN = 'E218';
    const CODE_ADNET_GMOR = 'E219';

    const CODE_BONUS = 'E301';              //[적립]보너스 그냥 보너스 지급 gift
    const CODE_ADMIN = 'E302';              //[적립]Cashtree 무상적립  관리자가 무상으로 넣어줌 gift
    const CODE_PARTNER_PAY_GIFT = 'E303';   //[적립]{광고주 적립 문구} 광고주(파트너)의 요청에 따라 무상적립 - 돈 안받고 적립해주는 것임. gift
    const CODE_RCMD = 'E501';               //[적립]추천 가입 가입시 추천인을 입력해서 적립금을 받은 경우 gift
    const CODE_INVITE_JOIN = 'E502';        //[적립]추천인 가입, 친구가 나를 추천인으로 입력하여 가입한 경우 gift
    const CODE_PHONE_AUTH = 'E503';         //[적립]전화번호인증, 보상 전화번호 인증하면 1회 보상금 지급 gift
    const CODE_WEEKLY_EVENT = 'E504';       //[적립]위클리 이벤트 적립금 지급 gift
    const CODE_LUCKY_CHANCE = 'E505';       //[적립]Lucky Chance gift
    const CODE_INVITE_BONUS = 'E506';       //[적립]초대가입자 활동 보너스 gift
    const CODE_INVITE_BONUS2 = 'E507';       //[적립]초대가입자 활동 보너스 gift
    const CODE_PHONE_AUTH2 = 'E508';         //[적립]전화번호인증, 보상 전화번호 인증하면 1회 보상금 지급 gift
    const CODE_PULSA_CASHBACK = 'E509';         //[적립]뿔사 캐시백
    const CODE_COUPON_CODE = 'E510';         //[적립]쿠폰

    const CODE_ATTENDANCE = 'E511';       //[적립]출석부
    const CODE_GAMECENTER_PRIZE = 'E512';       //[적립]게임센터 경품
    const CODE_VOUCHER_CASHBACK = 'E513';       //[적립]바우처 캐시백

    const CODE_FRUIT_GAME_WIN = 'E701';     //[적립]과일 이벤트 당첨  과일 이벤트 당첨 gift
    const CODE_AB_GAME_WIN = 'E702';     //[적립]사다리 게임 당첨

    const CODE_DEPOSIT_INVITE = 'E601';       //[무료충전]초대가입자
    const CODE_DEPOSIT_CASHBACK = 'E602';       //[무료충전]캐쉬백
    const CODE_DEPOSIT_PULSA_MISSION = 'E603';       // RIDCAT MISSION
    const CODE_TOP_UP_VOUCHER = 'E604';
    const CODE_BIDDING_WINNER = 'E605';
    const CODE_LEADERBOARD_WINNER = 'E606';

    //적립취소
    const CODE_MINUS_EARN = 'E906';         //[적립취소]적립금 회수(유료적립금) 활동적립금 적립금 회수
    const CODE_MINUS_GIFT = 'E907';         //[적립취소]적립금 회수(무료적립금) 무상적립금 적립금 회수
    const CODE_MINUS_EARN_DUP = 'E908';     //[적립취소]중복적립 (유료적립금)  유상적립금 적립금 회수 (음수로 저장) gift
    const CODE_MINUS_GIFT_DUP = 'E909';     //[적립취소]중복적립 (무료적립금)  무상적립금 적립금 회수 (음수로 저장) gift

    //뿔사
    const USING_CANCEL_PULSA = 'U601';      //[적립]뿔사구입 취소, 뿔사 교환 취소 earn/gift
    const USING_CANCEL_PHONE = 'U603';      //[적립]뿔사구입 취소, 뿔사 교환 취소 earn/gift
    const USING_CANCEL_LTDSHOP = 'U605';      //[적립]폰 구입 취소
    const USING_CANCEL_ECASH = 'U606';
    const USING_CANCEL_FHNSHOP = 'U607';
    const USING_CANCEL_SPONSOREDSHOP = 'U608';
    const USING_CANCEL_BIDDINGSHOP = 'U609';


    //뿔사, 수치가 -가 되어야 함
    const USING_PAY_PULSA = 'U901';         //[출금]뿔사 구입, 뿔사로 교환한 경우
    const USING_PAY_VOUCHER = 'U902';       //[출금]voucher 구입
    const USING_PAY_PHONE = 'U903';         //[출금]폰 구입
    const USING_PAY_LTDSHOP = 'U905';       //[출금]Limited Shop 구입
    const USING_PAY_ECASH = 'U906';         //[출금]ECash 구입
    const USING_PAY_FHNSHOP = 'U907';       //[출금]Fashion Shop 구입
    const USING_PAY_SPONSOREDSHOP = 'U908'; //[출금]Sponsored Shop 구입
    const USING_PAY_BIDDINGSHOP = 'U909';   //[출금]Bidding Shop 구입
    const USING_PAY_AIDO_MPL = 'U910';
    const USING_PAY_WEEKEND_EVENT = 'U911';
    const USING_PAY_POINT = 'U912';

    const USING_FRUIT_GAME = 'U931';        //[출금]과일당첨 이벤트 참여, 과일 이벤트에 참여하기 위해 적립금 차감 (음수로 저장)
    const USING_AB_GAME = 'U932';           //[출금]사다리 게임 참여 (음수로 저장)
    const USING_HOROSCOPE_GAME = 'U933';    //[출금]운세
    const USING_LOVECALC_GAME = 'U935';     //[출금]💞
    const USING_EXTERNAL = 'U999';          //

    const USING_PAY_CCC = 'U801';
	
	// BIO ENTRY
	const REWARD = 10;
	const CODE_GENDER = 91;
	const CODE_BIRTH = 92;
	const CODE_MARRIAGE = 93;
	const CODE_RELIGION = 94;

	const CODE_EDU = 102;

	const CODE_CAR = 104;
	const CODE_JOB = 105;
	const CODE_ADDR = 106;
	const CODE_CARD = 107;
	const CODE_BANK = 108;

	const CODE_VEHICLE = 109;
	const CODE_PET = 110;
	const CODE_GRADUATED = 111;

	const CODE_HAVE_CHILD = 112;
	const CODE_INCOME = 113;
	const CODE_SMOKING = 114;


	const AD_TYPE = [
		'cpc' => Code::CODE_CPC,         //visit
		'cpv' => Code::CODE_CPV,         //video
		'cpi' => Code::CODE_CPI,         //install
		'cpe' => Code::CODE_CPE,         //mission
		'cpr' => Code::CODE_CPR,         //sign up
	];

	public static $labels = [
		Code::CODE_RELEASE => 'Release',
		Code::CODE_CPC => 'CPC',
		Code::CODE_CPV => 'CPV',
		Code::CODE_CPI => 'CPI',
		Code::CODE_CPE => 'CPE',
		Code::CODE_CPR => 'CPR',
		Code::CODE_VIRALAD => 'ViralAD',
		Code::CODE_PARTNER_PAY => 'Bonus(partner)',

		Code::CODE_ADNET_ADXMI => 'ADXMI',
		Code::CODE_ADNET_SUPERS => 'SuperSonic',
		Code::CODE_ADNET_VUNGLE => 'Vungle',
		Code::CODE_ADNET_TAPJOY => 'Tapjoy',
		Code::CODE_ADNET_FYBER => 'Fyber',
		Code::CODE_ADNET_VALUEPOTION => 'ValuePotion',
		Code::CODE_ADNET_MOBVISTA => 'Mobvista',
		Code::CODE_ADNET_ADPOPCORN => 'ADPopCorn',
		Code::CODE_ADNET_GMOR => 'GMO Research',

		Code::CODE_BONUS => 'Bonus',
		Code::CODE_ADMIN => 'Admin',
		Code::CODE_PARTNER_PAY_GIFT => 'Partner pay gift',
		Code::CODE_RCMD => 'Invite',
		Code::CODE_INVITE_JOIN => 'Invite referrel',
		Code::CODE_PHONE_AUTH => 'Phone auth',
		Code::CODE_PHONE_AUTH2 => 'Phone auth(1more)',
		Code::CODE_PULSA_CASHBACK => 'Pulsa Cashback',
		Code::CODE_VOUCHER_CASHBACK => 'Voucher Cashback',
		Code::CODE_COUPON_CODE => 'Coupon Code',
		Code::CODE_WEEKLY_EVENT => 'Weelky',
		Code::CODE_LUCKY_CHANCE => 'Lucky',
		Code::CODE_INVITE_BONUS => 'Chidren',
		Code::CODE_INVITE_BONUS2 => 'Chidren(1000 Rp)',
		Code::CODE_ATTENDANCE => 'Attendance',
		Code::CODE_GAMECENTER_PRIZE => 'Game Coupon',

		Code::CODE_FRUIT_GAME_WIN => 'Game-Fruit',
		Code::CODE_AB_GAME_WIN => 'Game-A/B',

		Code::CODE_DEPOSIT_INVITE => 'Deposit - Invite',
		Code::CODE_DEPOSIT_CASHBACK => 'Deposit - Cashback',
        Code::CODE_DEPOSIT_PULSA_MISSION => 'Reward - Pulsa Mission',
        Code::CODE_TOP_UP_VOUCHER => 'Top Up Poin - Voucher',
        Code::CODE_BIDDING_WINNER => 'Top Up Poin - Bidding Winner',
        Code::CODE_LEADERBOARD_WINNER => 'Top Up Poin - Leaderboard Winner',


		Code::CODE_MINUS_EARN => 'Minus(work)',
		Code::CODE_MINUS_GIFT => 'Minus(free)',
		Code::CODE_MINUS_EARN_DUP => 'Minus(work,dup)',
		Code::CODE_MINUS_GIFT_DUP => 'Minus(free,dup)',

		Code::USING_CANCEL_PULSA => 'Pulsa cancel',
		Code::USING_CANCEL_PHONE => 'Phone cancel',
		Code::USING_CANCEL_LTDSHOP => 'Limited Shop cancel',
        Code::USING_CANCEL_FHNSHOP => 'Fashion Shop cancel',
        Code::USING_CANCEL_SPONSOREDSHOP => 'Sponsored Shop cancel',
		Code::USING_CANCEL_ECASH => 'ECash Refund',

		Code::USING_PAY_PULSA => 'Pulsa',
		Code::USING_PAY_VOUCHER => 'Voucher',
		Code::USING_PAY_LTDSHOP => 'Limited Shop',
		Code::USING_PAY_ECASH => 'ECash',
		Code::USING_PAY_PHONE => 'Phone',
        Code::USING_PAY_FHNSHOP => 'Fashion Shop',
        Code::USING_PAY_SPONSOREDSHOP => 'Sponsored Shop',
        Code::USING_CANCEL_BIDDINGSHOP => 'Cashtree Bidding',
		Code::USING_FRUIT_GAME => 'Game-Fruit',
		Code::USING_AB_GAME => 'Game-A/B',
		Code::USING_HOROSCOPE_GAME => 'Horoscope',
		Code::USING_LOVECALC_GAME => 'LoveCalc',
		Code::USING_EXTERNAL => 'External',

        Code::USING_PAY_BIDDINGSHOP => 'Bidding', // analityc bidding shop
        Code::USING_PAY_WEEKEND_EVENT => 'Bidding',
	];

	private static $msgCodes = [
		Code::CODE_RELEASE => 'bonus_cash_by_unlock',
		Code::CODE_CPC => 'cash_by_visiting',
		Code::CODE_CPV => 'cash_by_watching',
		Code::CODE_CPI => 'cash_by_install',
		Code::CODE_CPE => 'cash_by_mission',
		Code::CODE_CPR => 'cash_by_retention',
		Code::CODE_VIRALAD => 'cash_for_sharing_app_VAL',
		Code::CODE_PARTNER_PAY => 'bonus_cash_by',

		Code::CODE_ADNET_ADXMI => 'cash_by_network',
		Code::CODE_ADNET_SUPERS => 'cash_by_network',
		Code::CODE_ADNET_VUNGLE => 'cash_by_network',
		Code::CODE_ADNET_TAPJOY => 'cash_by_network',
		Code::CODE_ADNET_FYBER => 'cash_by_network',
		Code::CODE_ADNET_VALUEPOTION => 'cash_by_network',
		Code::CODE_ADNET_MOBVISTA => 'cash_by_network',
		Code::CODE_ADNET_ADPOPCORN => 'cash_by_network',
		Code::CODE_ADNET_GMOR => 'cash_by_network',

		Code::CODE_BONUS => 'bonus_cash',
		Code::CODE_ADMIN => 'bonus_cash_cashtree',
		Code::CODE_PARTNER_PAY_GIFT => 'cash_by',
		Code::CODE_RCMD => 'bouns_cash_inviting',
		Code::CODE_INVITE_JOIN => 'bonus_cash_referral',
		Code::CODE_PHONE_AUTH => 'bonus_cash_phone_auth',
		Code::CODE_PHONE_AUTH2 => 'bonus_cash_phone_auth',
		Code::CODE_PULSA_CASHBACK => 'bonus_cash',
		Code::CODE_VOUCHER_CASHBACK => 'bonus_cashback_voucher',
		Code::CODE_COUPON_CODE => 'bonus_cash',
		Code::CODE_WEEKLY_EVENT => 'Code_code_weekly_event_msg',
		Code::CODE_LUCKY_CHANCE => 'Code_code_lucky_chance',
		Code::CODE_INVITE_BONUS => 'Code_code_invite_bonus',
		Code::CODE_INVITE_BONUS2 => 'Code_code_invite_bonus',
		Code::CODE_ATTENDANCE => 'Code_code_attendance',
		Code::CODE_GAMECENTER_PRIZE => 'coupon_game',

		Code::CODE_FRUIT_GAME_WIN => 'Code_code_fruit_event_win',
		Code::CODE_AB_GAME_WIN => 'Code_code_game_ab_win',

		Code::CODE_DEPOSIT_INVITE => 'Code_code_deposit_invite',
		Code::CODE_DEPOSIT_CASHBACK => 'Code_code_deposit_cashback',
        Code::CODE_DEPOSIT_PULSA_MISSION => 'Code_code_deposit_pulsa_mission',
        Code::CODE_TOP_UP_VOUCHER => 'Code_top_up_point',
        //winner
        Code::CODE_BIDDING_WINNER => 'Code_bidding_winner',
        Code::CODE_LEADERBOARD_WINNER => 'Top Up Poin - Leaderboard Winner',

		Code::CODE_MINUS_EARN => 'withdrawal_cash',
		Code::CODE_MINUS_GIFT => 'withdrawal_bonus_cash',
		Code::CODE_MINUS_EARN_DUP => 'Code_code_minus_earn_dup',
		Code::CODE_MINUS_GIFT_DUP => 'Code_code_minus_gift_dup',

		Code::USING_CANCEL_PULSA => 'pulsa_purchase_cancel',
		Code::USING_CANCEL_PHONE => 'phone_purchase_cancel',
		Code::USING_CANCEL_LTDSHOP => 'ltdshop_purchase_cancel',
        Code::USING_CANCEL_FHNSHOP => 'fhnshop_purchase_cancel',
        Code::USING_CANCEL_SPONSOREDSHOP => 'sponsoredshop_purchase_cancel',
        Code::USING_CANCEL_BIDDINGSHOP => 'cashtreebidding_bid_cancel',
		Code::USING_CANCEL_ECASH => 'ecash_purchase_cancel',

		Code::USING_PAY_PULSA => 'buy_pulsa',
		Code::USING_PAY_VOUCHER => 'buy_voucher',
		Code::USING_PAY_LTDSHOP => 'buy_ltdshop',
        Code::USING_PAY_FHNSHOP => 'buy_fhnshop',
        Code::USING_PAY_SPONSOREDSHOP => 'buy_sponsoredshop',
        Code::USING_PAY_BIDDINGSHOP => 'cashtree_bidding',
        Code::USING_PAY_WEEKEND_EVENT => 'cashtree_weekend_event',
        Code::USING_PAY_AIDO_MPL => 'mpl_aido_pulsa',
		Code::USING_PAY_ECASH => 'buy_ecash',
		Code::USING_PAY_PHONE => 'buy_phone',
		Code::USING_FRUIT_GAME => 'using_code_fruit_event',
		Code::USING_AB_GAME => 'using_code_game_ab',
		Code::USING_HOROSCOPE_GAME => 'using_code_horoscope',
		Code::USING_LOVECALC_GAME => 'using_code_lovecalc',
		Code::USING_EXTERNAL => 'using_code_external',
        Code::USING_PAY_CCC => 'cerdas_cermat',
		
		// FOR BIO ENTRY
		Code::CODE_GENDER => 'gender',
		Code::CODE_BIRTH => 'birth',
		Code::CODE_RELIGION => 'religion',
		Code::CODE_MARRIAGE => 'marriage',
		Code::CODE_HAVE_CHILD => 'have_child',
		Code::CODE_INCOME => 'income',
		Code::CODE_SMOKING => 'smoking',

		Code::CODE_VEHICLE => 'private_transportation',
		Code::CODE_PET => 'pet',
		Code::CODE_GRADUATED => 'have_you_graduated_from_college',

		Code::CODE_ADDR => 'address',
		Code::CODE_EDU => 'education',
		Code::CODE_JOB => 'job',
		Code::CODE_BANK => 'do_you_have_a_bank_account',
		Code::CODE_CARD => 'do_you_have_a_credit_card',
	];

	//=== false 이면 푸쉬를 보내지 않고, 값이 있으면 보내고, 없으면 'got_rp' 를 보낸다
	private static $pushMsgCode = [
		Code::CODE_RCMD => 'bouns_cash_inviting_father',
		Code::CODE_RELEASE => false,
		Code::CODE_INVITE_BONUS => false,
		Code::CODE_INVITE_BONUS2 => false,

		Code::USING_CANCEL_PULSA => false,
		Code::USING_CANCEL_PHONE => false,
		Code::USING_CANCEL_LTDSHOP => false,
		Code::USING_CANCEL_ECASH => false,
	];

	private static $parentOffsetCodes = [
		Code::CODE_RELEASE,
		Code::CODE_CPC,
		Code::CODE_CPI,
		Code::CODE_CPE,
		Code::CODE_CPV,
		Code::CODE_VIRALAD,

		//ad network
		Code::CODE_ADNET_ADXMI,
		Code::CODE_ADNET_SUPERS,
		Code::CODE_ADNET_VUNGLE,
		Code::CODE_ADNET_TAPJOY,
		Code::CODE_ADNET_FYBER,
		Code::CODE_ADNET_VALUEPOTION,
		Code::CODE_ADNET_MOBVISTA,
		Code::CODE_ADNET_ADPOPCORN,
		Code::CODE_ADNET_GMOR,

		Code::CODE_RCMD, //invite
		Code::CODE_PHONE_AUTH,
	];
    const ADCOLONY_ZONE_ID = ['18'=>'vz3526e166b8ed4ddea2' , '19'=>'vz5ab55c75fe154bcbb2' ,'skyblaster' => 'vz58053d9fb3f74f1798'];
    const INMOBY_PLACEMENT_ID = ['18'=>'1595188844203' , '19'=>'1592941605245' ,'17' => '1593832416993'];

    public static function getLang($code){
        return static::$msgCodes[$code];
    }
	
}