<?php
use think\facade\Env;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 常用设置
// +----------------------------------------------------------------------
$speciality = trim(file_get_contents('../extend/speciality.txt'));//大学专业
$surname = trim(file_get_contents('../extend/surname.txt'));//大学专业
$city = trim(file_get_contents('../extend/city.txt'));//城市
$job = trim(file_get_contents('../extend/job.txt'));//职业
$industry = trim(file_get_contents('../extend/industry.txt'));//行业

return [
    'mail' => [
        'host' => 'md-hk-3.webhostbox.net',
        'smtpauth' => TRUE,
        'username' => 'smtp@kooa.ai',
        'from' => 'smtp@kooa.ai',
        'fromname' => 'sales.kooa.ai',
        'password' => 'i&mVKH6FT2pc',//邮箱授权码
        'charset' => 'utf-8',
        'ishtml' => TRUE,
        'port'=>465,
        'smtpsecure'=>'ssl',
        'smtpdebug'=>4,
    ],
    'sendMessage'=>[
        'accessKeyId'=>'LTAI3NfY6JVzdFI7',
        'accessKeySecret'=>'uyAiiBCMMBAyDmkjo59TZOXhrhHNHP',
        'signName'=>'魔方科技',
        'templateCode'=>'SMS_137865176',
    ],
    'level' => 1,
    //推广会员层级
    'api_token' => 'GbwS8JFxJfW3uj86S',

    'redis'=>[
        'host'=>'127.0.0.1',
        'port'=>'6379',
        'author'=>'',
        'db_index'=>0,
    ],
    // 'api_key' => $basePath['api_key']
    'resume_title' => [
        //标题替换成key用于分组匹配
        '基本资料' => 'basicData',
        '求职意向'  =>  'basicData',
        '工作经历'  =>  'workExperience',
        '工作经验'  =>  'workExperience',
        '工作业绩'  =>  'workExperience',
        '教育经历'  =>  'educationalBackground',
        '教育背景'  =>  'educationalBackground',
        '项目经验'  =>  'projectExperience',
        '项目经历'  =>  'projectExperience',
        '简历内容'  =>  'projectExperience',
        '自我评价'  =>  'selfEvaluation',
        '自我介绍'  =>  'selfEvaluation',
        '自我描述'  =>  'selfEvaluation',
        '技能特长'  =>  'skillExpertise',
        '专业技能'  =>  'skillExpertise',
        '技能专长'  =>  'skillExpertise',
        '目前状况'  =>  'basicData',
        '作品展示'  =>  'basicData',
        '语言能力'  =>  'language',
        '证书'     =>    'certificate',
        '获奖证书' =>   'certificate',
        '培训经历' =>   'train',
        '附加信息' =>  'other',
        '在校情况' =>  'atSchool'
    ],

    'resume_rule' => [ //这个配置用于标题关键字隔行分离，便于匹配
        '基本资料',
        '自我评价',
        '目前状况',
        // '工作经历',
        '教育经历',
        '教育背景',
        '项目经验',
        '项目经历',
        '简历内容',
        '技能特长',
        '技能专长',
        '专业技能',
        '求职意向',
        // '招聘网',
        '软件环境',
        '硬件环境',
        '项目描述',
        '责任描述',
        '开发工具',
        '(离职,正在找工作)',
        '获奖证书'
        // '。'
    ],

    //标题集合
    'group_list' => [
        '基本资料',
        '自我评价',
        '自我描述',
        '自我介绍',
        '目前状况',
        '工作经历',
        '工作业绩',
        '工作经验',
        '教育经历',
        '教育背景',
        '项目经验',
        '项目经历',
        '简历内容',
        '技能特长',
        '技能专长',
        '专业技能',
        '求职意向',
        '作品展示',
        '证书',
        '获奖证书',
        '语言能力',
        '培训经历',
        '附加信息',
        '在校情况'
    ],

    'group_title' => "/^(基本资料|自我评价|自我描述|自我介绍|目前状况|工作经历|工作业绩|工作经验|教育经历|教育背景|项目经验|项目经历|简历内容|技能特长|技能专长|专业技能|求职意向|作品展示|证书|获奖证书|语言能力|培训经历|附加信息|在校情况)/",
    //标题分组后匹配

    'base_rule' => [ 
    //这个配置用于基本资料隔行分离，提高匹配的准确率
        '姓名',
        '手机',
        '现居住地',
        '到岗时间',
        '提供住房',
        '当前状态',
        '简历更新时间',
        '英语水平',
    ],
    'base_replace_blank' => "/(基本资料|ID:\d+|招聘网|匹配度(:|：)\d{2}%)|个人简历|招聘|(j\d+)/i",
    //这个配置用于基本资料特定关键字过滤，提高匹配准确率

    'date_rule' => "/\d{4}(\/|\.|-)\d{1,2}(至|--|-)(\d{4}(\/|\.|-)\d{1,2}|至今)/",
    //用于匹配时间格式

    'basicData' => [
    //基本资料的匹配规则集合
        // 'source' => "/(智联|前程|中华英才|中国人才|拉钩)/",
        'name' => "/($surname)([\x{4e00}-\x{9fa5}]{1,2})/u",
        'sex' => "/(男|女)/",
        'age' => "/\d{2}岁/",
        'phone' => "/0?(13|14|15|17|18|19)[0-9]{9}/",
        'email' => "/\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}/",
        'work_year' => "/(\d{1,2}|\d{1,2}.\d+|[\x{4e00}-\x{9fa5}]+)(年|年以上)工作经验/u",
        // 'hight' => "/\d{3}(cm|CM)/",
        # 'birthday' => "/\d{4}(年|\.|\/|\-)\d{1,2}(月|\.|\/|\-)\d{1,2}(日)?/",
        'birthday' => "/\d{4}(年|\.|\/)\d{1,2}(月|\.|\/)(\d{1,2})?(日)?/",
        // 'political' => "/(党员|群众|普通公民|团员)/",
        // 'nation' => "/(汉(族)?|蒙古(族)?|回(族)?|藏(族)?|维吾尔(族)?|苗(族)?|彝(族)?|壮(族)?|布依(族)?|朝鲜(族)?|满(族)?|侗(族)?|瑶(族)?|白(族)?|土家(族)?|哈尼(族)?|哈萨克(族)?|傣(族)?|黎(族)?|僳僳(族)?|佤(族)?|畲(族)?|高山(族)?|拉祜(族)?|水(族)?|东乡(族)?|纳西(族)?|景颇(族)?|柯尔克孜(族)?|土(族)?|达斡尔(族)?|仫佬(族)?|羌(族)?|布朗(族)?|撒拉(族)?|毛南(族)?|仡佬(族)?|锡伯(族)?|阿昌(族)?|普米(族)?|塔吉克(族)?|怒(族)?|乌孜别克(族)?|俄罗斯(族)?|鄂温克(族)?|德昂(族)?|保安(族)?|裕固(族)?|京(族)?|塔塔尔(族)?|独龙(族)?|鄂伦春(族)?|赫哲(族)?|门巴(族)?|珞巴(族)?|基诺(族)?)/",

        // 'domicile' => "/(居住地|所在地|目前所在地|目前居住地)(:|：)?(北京(市)?|天津(市)?|上海(市)?|重庆(市)?|河北(省)?|山西(省)?|辽宁(省)?|吉林(省)?|黑龙江(省)?|江苏(省)?|浙江(省)?|安徽(省)?|福建(省)?|江西(省)?|山东(省)?|河南(省)?|湖北(省)?|湖南(省)?|广东(省)?|海南(省)?|四川(省)?|贵州(省)?|云南(省)?|陕西(省)?|甘肃(省)?|青海(省)?|西藏自治(区)?|广西壮族自治(区)?|内蒙古自治(区)?|宁夏回族自治(区)?|新疆维吾尔自治(区)?|香港特别行政(区)?|澳门地(区)?|台湾(省)?|深圳(市)?)(\-|市|区)?([\x{4e00}-\x{9fa5}]+)?(\-|区)?([\x{4e00}-\x{9fa5}]+)?/u",

        // 'domicile' => "/(现居住|居住地|目前所在地|目前居住地|通讯地址)(:|：)?($city)?(省|\-|市|区)?($city)(\-|区)?([\x{4e00}-\x{9fa5}]+)?/u",

        // 'native_place' => "/(籍贯|户口)(:|：)([\x{4e00}-\x{9fa5}]+)/u",
        'expected_money' => "/(期望薪水|期望薪资|期望月薪)(:|：)?(\d+(\-)?(\d+)?(万以上|\/月|\/年|\/月以上|\/年以上|元\/月|元\/年)?|\d+)/u",
        // 'expected_industry'  =>  "/(期望从事行业|希望行业|期望行业|意向行业)(:|：)?(.*)?/u",
        'expected_address'  =>  "/(目标地点|期望地点|地点|工作地点|意向地区|期望工作地区)(:|：)(.*)?/",
        // 'expected_job'  =>  "/(期望从事职业|目标职能|意向岗位|职能\/职位)(:|：)?(.*)?/u",
        // 'domicile' => "/(目前所在地)(:|：)?(.*)?/u",
        'native_place' => "/(籍贯(.*)|户口(.*))(:|：)([\x{4e00}-\x{9fa5}]+)/u",
        'self_evaluation' => "/(自我介绍|自我评价)(:|：)?(\n)?(.*)?/u",
        'status' => "/([\x{4e00}-\x{9fa5}]+)(离职|在职|观望|找工作)([\x{4e00}-\x{9fa5}]*)/u",
        // 'postal_code' => "/(邮编|邮政|邮政编码)(:|：)?(\d{6})/",
        // 'school' => "/[^至今：:\d]([\x{4e00}-\x{9fa5}]+)(大学|学院|学校)/u",
        // 'educational' => "/(初中|高中|大专|本科|硕士|博士|研究生)/",
        // 'speciality' => "/((专业)(:|：)([\x{4e00}-\x{9fa5}]+))|((大学|学院|学校)([\x{4e00}-\x{9fa5}]+)[^初中高中大专本科硕士博士研究生\d])|(.*)(大学|学院|学校)/u",
        // 'speciality' => "/($speciality)/u",
        'english' => "/(英语水平|英语)?(:|：)?(专业|大学)(英语)?(4|四|6|六|八|8|tem4|tem8|cet4|cet6|cet8)(级)?/i",
        'nearest_job' => "/(.*)职位(:|：)(.*)/",
        'nearest_unit' => "/(.*)公司(:|：)($city)?(.*)(中关村|公司|集团|超市|科技|酒店|局|学校|网络|通|伟业|厂|行)/"

    ],

    'educationalBackground' => [
    //教育经历匹配集合
        'graduation_time' => "/(\d{4}(\/|\.)\d{1,2}(至|--|-)(\d{4}(\/|\.)\d{1,2}|至今))|(\d{4}年毕业)|(\d{4}年\d{1,2}月毕业)|(\d{4}(至|--|-)(\d{4}|至今))/",
        'school' => "/[^至今：:\d-]([\x{4e00}-\x{9fa5}]+)(大学|学院|学校)/u",
        'speciality' => "/($speciality)/u",
        'educational' => "/(初中|高中|大专|统招大专|自考大专|民办大专|本科|统招本科|自考本科|民办本科|硕士|博士|研究生|无学历)/",
        'speciality_info' => "/(专业描述|描述|详细情况|)(:|：)/",
        
    ],

    'workExperience' => [
    //工作经历匹配集合
        'work_rule' => "/([\x{4e00}-\x{9fa5}]+(\d{4}(\/|\.|-)\d{1,2}(至|--|-|－)(\d{4}(\/|\.|-|－)\d{1,2}|至今)))|((\d{4}(\/|\.|-)\d{1,2}(至|--|-|－)(\d{4}(\/|\.|-|－)\d{1,2}|至今))(:|：)?[\x{4e00}-\x{9fa5}]+)/u",
        'work_time' => "/\d{4}(\/|\.|-)\d{1,2}(至|--|-|－)(\d{4}(\/|\.|-)\d{1,2}|至今)/",
        'company_name' => "/[^至今：:\d-\/](.*)((\(|\[|（)(\d+(年)|\d+(年)\d+(个月)|\d+(个月))(\)|\]|）))/u",
        'time_total' => "/((\(|\[)(\d+(年)|\d+(年)\d+(个月)|\d+(个月))(\)|\]))/",
        'money' => "/\d+元\/(月|年)(以上|以下)?/",
        'content' => "/(.*)(协助|负责|项目|责任|描述|参与|产品|需求|开发|\d\,|\d\.|\d、)(.*)/",
        'content' => "/(.*)(带领|编写|工作|描述|负责|1,|1\.|1、)(.*)/",
        'company_name' => "/(中关村|公司|集团|超市|科技|酒店|$city|局|学校|网络|通|伟业|厂|行)/u",
        'company' => "/(($city)(.*)(公司|集团|超市|科技|酒店|局|学校|网络|通|伟业|厂)?((\(|（|\[)(\d+年\d+个月|\d+年|\d+个月)?(\)|）|\]))?)|(($city)?(.*)(公司|集团|超市|科技|酒店|在线|局|学校|通|伟业|厂)((\(|（|\[)(\d+年\d+个月|\d+年|\d+个月)?(\)|）|\]))?)/u",
        'job' => "/($job)/u",
        'industry' => "/所属行业|($industry)/u",
        'special_characters' => "/(,|，|。|;|；|、|：|:)/"

    ],

    'projectExperience' => [
        'project_rule' => "/((.*)(\d{4}(\/|\.|-)\d{1,2}(至|--|-|－)(\d{4}(\/|\.|-|－)\d{1,2}|至今|现在)))|((\d{4}(\/|\.|-|－)\d{1,2}(至|--|-|－)(\d{4}(\/|\.|-|－)\d{1,2}|至今|现在))(:|：)?(.*))/u",
        'project_time' => "/\d{4}(\/|\.|-|－)\d{1,2}(至|--|-|－)(\d{4}(\/|\.|-|－)\d{1,2}|至今|现在)/",
        // 'project_name' => "/(\d{4}(\/|\.|-)\d{1,2}(至|--|-)(\d{4}(\/|\.|-)\d{1,2}|至今)(.*))/u",
        'envy_responsibility' => "/(责任描述|项目责任|项目职责)(.*)/",
        // 'content' => "/(.*)(项目描述|1\,|1\.|1、)(.*)/",
        'content' => "/^(项目描述)/",
        'special_characters' => "/(,|，|。|;|；|:|：|\.)/",
        'project_name' => "/([^描述](.*)?(系统|结算|平台|项目|app|APP|客户|手机|后台|前台|网|在线|商|客户端|服务端|api|oms|h5|web|android|ios|金|宝|语音|企业|电话|会议|业务|票|通|信|智)(.*)?)|(^[A-Za-z])/i",
        // 'project_name1' => "/[A-Za-z]/",
        'job' => "/($job)/u",
    ],

    'project_continue_field' => "/^(软件环境|硬件环境|开发工具|责任描述|当前状态)/",
    //匹配项目时候跳过的字段
    'project_name_field' => "/^(项目描述|项目职责|项目责任|(\d+)|（\d+）)/",
    //项目名过滤
    'project_job_field' => "/^(项目描述|项目职责|项目责任|(\d+)|（\d+）)/",
    //项目职位/负责过滤

    // 'selfEvaluation' => [ 'self_evaluation' => "/\+/" ],
    'jobIntention' => [

        'expected_money' => "/(期望薪水|期望薪资|期望月薪)(:|：)?(\d+\-\d+|\d+(万以上)?)/",
        'expected_industry'  =>  "/(希望行业|期望行业|意向行业)(:|：)?(.*)?/u",
        'expected_address'  =>  "/(目标地点|期望地点|地点|工作地点|意向地区)(:|：)?(.*)?/u",
        'expected_job'  =>  "/(目标职能|职位|意向岗位)(:|：)?(.*)?/u",
        'domicile' => "/(目前所在地)(:|：)?(.*)?/u",
        'native_place' => "/(籍贯|户口|户口所在地)(:|：)([\x{4e00}-\x{9fa5}]+)/u",
    ],

    'rule' => [
        'sex' => "/(男|女)/",
        'age' => "/\d{2}岁/",
        'phone' => "/0?(13|14|15|17|18|19)[0-9]{9}/",
        'email' => "/\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}/",
        'work_year' => "/(\d{1,2}|\d{1,2}.\d+|[\x{4e00}-\x{9fa5}]+)(年|年以上)工作经验/u",
        'hight' => "/\d{3}(cm|CM)/",
        # 'birthday' => "/\d{4}(年|\.|\/|\-)\d{1,2}(月|\.|\/|\-)\d{1,2}(日)?/",
        'birthday' => "/\d{4}(年|\.|\/)\d{1,2}(月|\.|\/)\d{1,2}(日)?/",
        'political' => "/(党员|群众|普通公民|团员)/",
        'nation' => "/民族:?(汉(族)?|蒙古(族)?|回(族)?|藏(族)?|维吾尔(族)?|苗(族)?|彝(族)?|壮(族)?|布依(族)?|朝鲜(族)?|满(族)?|侗(族)?|瑶(族)?|白(族)?|土家(族)?|哈尼(族)?|哈萨克(族)?|傣(族)?|黎(族)?|僳僳(族)?|佤(族)?|畲(族)?|高山(族)?|拉祜(族)?|水(族)?|东乡(族)?|纳西(族)?|景颇(族)?|柯尔克孜(族)?|土(族)?|达斡尔(族)?|仫佬(族)?|羌(族)?|布朗(族)?|撒拉(族)?|毛南(族)?|仡佬(族)?|锡伯(族)?|阿昌(族)?|普米(族)?|塔吉克(族)?|怒(族)?|乌孜别克(族)?|俄罗斯(族)?|鄂温克(族)?|德昂(族)?|保安(族)?|裕固(族)?|京(族)?|塔塔尔(族)?|独龙(族)?|鄂伦春(族)?|赫哲(族)?|门巴(族)?|珞巴(族)?|基诺(族)?)/",

        'domicile' => "/(居住地|所在地|目前所在地|目前居住地)(:|：)?(北京(市)?|天津(市)?|上海(市)?|重庆(市)?|河北(省)?|山西(省)?|辽宁(省)?|吉林(省)?|黑龙江(省)?|江苏(省)?|浙江(省)?|安徽(省)?|福建(省)?|江西(省)?|山东(省)?|河南(省)?|湖北(省)?|湖南(省)?|广东(省)?|海南(省)?|四川(省)?|贵州(省)?|云南(省)?|陕西(省)?|甘肃(省)?|青海(省)?|西藏自治(区)?|广西壮族自治(区)?|内蒙古自治(区)?|宁夏回族自治(区)?|新疆维吾尔自治(区)?|香港特别行政(区)?|澳门地(区)?|台湾(省)?)(\-|市|区)([\x{4e00}-\x{9fa5}]+)(\-|区)?([\x{4e00}-\x{9fa5}]+)?/u",

        'native_place' => "/(籍贯|户口)(:|：)([\x{4e00}-\x{9fa5}]+)/u",
        'expected_money' => "/(期望薪水|期望薪资|期望月薪)(:|：)?(\d+\-\d+|\d+(万以上)?)/",
        'expected_industry'  =>  "/(希望行业|期望行业|意向行业)(:|：)?(.*)?/u",
        'expected_address'  =>  "/(目标地点|期望地点|地点|工作地点|意向地区|期望工作地区)(:|：)?(.*)?/u",
        'expected_job'  =>  "/(目标职能|意向岗位)(:|：)?(.*)?/u",
        // 'domicile' => "/(目前所在地)(:|：)?(.*)?/u",
        'native_place' => "/(籍贯|户口|户口所在地)(:|：)([\x{4e00}-\x{9fa5}]+)/u",
        'self_evaluation' => "/(自我介绍|自我评价)(:|：)?(\n)?(.*)?/u",
        'school' => "/[^至今：:\d]([\x{4e00}-\x{9fa5}]+)(大学|学院|学校)/u",
        'educational' => "/(初中|高中|大专|本科|硕士|博士|研究生)/",
        // 'speciality' => "/((专业)(:|：)([\x{4e00}-\x{9fa5}]+))|((大学|学院|学校)([\x{4e00}-\x{9fa5}]+)[^初中高中大专本科硕士博士研究生\d])|(.*)(大学|学院|学校)/u",
        'speciality' => "/($speciality)/u",


    ],
        
    
];