<?php
namespace App\Helpers;
/**
 * Created by PhpStorm.
 * User: agusttampubolon
 * Date: 2021-05-19
 * Time: 14:54
 */

class Code {
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

    const USING_FRUIT_GAME = 'U931';        //[출금]과일당첨 이벤트 참여, 과일 이벤트에 참여하기 위해 적립금 차감 (음수로 저장)
    const USING_AB_GAME = 'U932';           //[출금]사다리 게임 참여 (음수로 저장)
    const USING_HOROSCOPE_GAME = 'U933';    //[출금]운세
    const USING_LOVECALC_GAME = 'U935';     //[출금]💞
    const USING_EXTERNAL = 'U999';          //

    const USING_PAY_CCC = 'U801';

    const ADCOLONY_ZONE_ID = ['18'=>'vz3526e166b8ed4ddea2' , '19'=>'vz5ab55c75fe154bcbb2' ,'skyblaster' => 'vz58053d9fb3f74f1798'];
    const INMOBY_PLACEMENT_ID = ['18'=>'1595188844203' , '19'=>'1592941605245' ,'17' => '1593832416993'];
}