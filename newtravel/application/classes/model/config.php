<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Config extends ORM
{
    /**
     * ���֧��֤���Ƿ���ڻ�֤��Ŀ¼
     * @param $config ϵͳ����
     * @param bool|false $returnCertPath ����֤��Ŀ¼
     * @return array
     */
    public static function is_exists_certs($config, $returnCertPath = false)
    {
        $certsPath = array(
            //4.0
            array(
                'chinabank' => array(
                    '/thirdpay/yinlian/certs/',
                ),
                'wxpay' => array(
                    '/thirdpay/weixinpay/cert/'
                )
            ),
            //4.0PC��5.0Mobile
            array(
                'chinabank' => array(
                    //'/thirdpay/yinlian/certs/', 4.0��5.0SDK��ͬ
                    '/payment/application/vendor/pc/chinabank/certs/'
                ),
                'wxpay' => array(
                    '/thirdpay/weixinpay/cert/',
                    '/payment/application/vendor/pc/wxpay/cert/'
                )
            ),
            //5.0
            array(
                'chinabank' => array('/payment/application/vendor/pc/chinabank/certs/'),
                'wxpay' => array('/payment/application/vendor/pc/wxpay/cert/'),
                'bill' => array('/payment/application/vendor/pc/bill/cert/'),
            )
        );
        $certs = array(
            'wxpay' => array('apiclient_cert.p12', 'apiclient_cert.pem', 'apiclient_key.pem', 'rootca.pem'),
            'chinabank' => array('zhengshu.pfx'),
            'bill' => array('public-rsa.cer'),
        );
        //���ݰ汾ѡ����Ҫ����Ŀ¼
        if ($config['cfg_pc_version'] == 0 && $config['cfg_mobile_version'] == 0)
        {
            $certsPath = $certsPath[0];
        }
        else if ($config['cfg_pc_version'] == 0 && $config['cfg_mobile_version'] == 1)
        {
            $certsPath = $certsPath[1];
        }
        else
        {
            $certsPath = $certsPath[2];
        }
        //�����ϴ�֤��Ŀ¼
        if ($returnCertPath)
        {
            return $certsPath;
        }
        //�����ļ�
        foreach ($certsPath as $k => $v)
        {
            $bool = false;
            foreach ($v as $sub)
            {
                if ($bool)
                {
                    break;
                }
                foreach ($certs[$k] as $filename)
                {
                    if (!file_exists(BASEPATH . $sub . $filename))
                    {
                        $bool = true;
                        break;
                    }

                }
            }
            if ($bool)
            {
                $info[$k] = false;
                continue;
            }
            else
            {
                $info[$k] = true;
            }
        }
        //���ؼ����
        return $info;
    }
}