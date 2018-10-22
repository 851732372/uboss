<?php
namespace Vendor\AliPay;
class Config
{
    public static function config()
    {
        return $config = array(
            //应用ID,您的APPID。
            'app_id' => "2018090161244107",

            //商户私钥，您的原始格式RSA私钥
            'merchant_private_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA6UTJrAfBr3c6D9T45K8I+ZZU9yKN23ZYscfiwmh30X/U3HeH7iIMXEB8b8pNYeqS0PhOxC9ZEQ+K6yNLI456L2Ehtp55a5UQDgqGQPjVKuLZVcFhj+4YZkiv9UKUTENaG5HQrRYudEMdZuYMpgZv1peLOO6QPGFowbyjMFceGCb+3gS7u2pSsaJ669fHpkdco7XKBBmwHqdd+7MHKs+Kmj52cWopRWeTvvIfj9khDQIbfIaw7C3HdtfpnC2PH/DOgVxauYbJ60dqUkq1xFYgAdJv0PbfF0S6oDgM+0BfVuW1548j0w/Q1FfAINmU8ay/wPbqMXTvivTw5udkwJs6PwIDAQAB",
                //"MIIEogIBAAKCAQEAuQ1DpDpsArQbo3OdMpCI8xN3ugeJmnbVelO6lWyrVXY90Hqjrl8qnlToMmE6PVi7RLGDjHi/z6PeaClTi32zdA/uAKCqZ/WzMe95rmJ9eo13Mto5sKADYvbyEOYWKIm5Wnff4ldv/xkKTcgn2cVUhSY4qxG2YEhNbHeMbQYcwTiNloNY8eHrrW3uM8l6iJOgR18pHlKs7dwzskd0sgOS943ts+XJB1Tbzk5DNn9zo/T8pI1xYOMKCej4vakCxCBBIh1Lp/O7C7vHvneTZxXRQw9MqioU+ZHiiJaKVdVDHWBVtHHoPn5HvrRXxADkGcF1FMPY20TOZi7yhN6nru3XKwIDAQABAoIBAAOCf+TugrvuZJPODo419ZL+rIenuvFmDZh7XA+Xdwxw6K06lj4paeqE276Q34ToWOztnPweEi1DA2XgWshLgwrRfocehPzhyRtKWnl3PsADTN53Cx8Red73phGx+3ubWRuhKGrnk6U3+V0OTcswlfDMj8iW1mmvFWDYR56Nvh/gBXJnLxIv/jgq2gJ4UBM/Fbnqpdn+OCiookdmyQgwAlqF3Fskc65NIRjzu7ezXHzBz14TbZnNnr39Ido+Rdw+pYHAJLHsibi6yIYW4UuqC8bOt3QBZsetLKSFgB1t2Bs9gSrQSh9PPpk+IoVbpvcZrniF8D+LGQKvYJMEjrijq2kCgYEA5mgOURWxn9oqp68DOQsE9G82os0WEhme+MJIFeF1UbM/XoMAtS5q5yh2zzz0zkuiP/WoXx7MmhtYOujNVlstfVblJItWoeyHO0gJHjudU8mTCIjhGwEFlsxeV/v7uXIm5muciB0RsNNAauRdZiKfHcjOXydxSqvETM2FyM4AEe8CgYEAzZt7hgh0lsJcKMuOVIdo/Miify7T8wCP1JJGint9DKOefoIUe7LaA98hqg0XRfbYlMBxZGLJ6o3/yHa2Kyus/3BCkIvHzbAXqbv15U1h809ejh/fkpD2gIWcxtub2PGI2UjyBCbozF4EjZ8jvAIfVwxw43aWO3u6F4brxONR2oUCgYAulg2HAY7nllIXk4BgUUUQM0hlxHfY8ws8mvO+UNbKzSZb8rr650bANGNJZbbN22cT7ZS8ntqZsju1tYqHEmpxNFeievXqSYvSRa9qSH14CoLLL/nr3toFco8E1TNBfL42yr1cnBwOl7gnpMLpushXWlm3zZ7PjUlkig2p21USSwKBgB6dDfFRBPsFiW9Rj6mqV8l7niZNtKUz6jZ+aR5pcJ8XJZw0hhQ89xUoffyx4ks6i9jqJngJ9YKFDhatBka7RBDsiBy5k1Be7ccKA3zW3sS5hpymxkUubGWKf2Nkg3nzzKp9n/taR8NWJFtp3Io7zqrJYW2c1JWawISMREKWjjN5AoGANGnOUiTNrSgvj+c0+jBSuvwqZ/iFTyru1pJAI+B4AJStx1gxwJ8DTc0RMlppchLq/ulQ8Y08rJJ6NbYmvTucHbrWJ0qe0NOBoDZ79vqrrA+oNYiDYkO5vPN0w6wLeD0GSFv4MGWvIwVmcn+3B4DPmGP+hvaxwt/KtSssiXHFBWs=",

            //异步通知地址
            'notify_url' => "",

            //同步跳转
            'return_url' => '',

            //编码格式
            'charset' => "UTF-8",

            //签名方式
            'sign_type' => "RSA2",

            //支付宝网关
            'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

            //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
            'alipay_public_key'=>"MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAk2Nx7iqgXKC1C8SW075wC969IJ20k3zZpgGL5RWYxqYms9+Q71VKcQehacIGURMGE62BAaMSjeWJyfGWtwSOlVhx6Zc9/eD2VkMBaINYu8Jvr60YTN1MUDrbrIVeuEs8n5wV00CKnOf06nB08NcP3MlXZ/Jn2RDj7JNGiLMHMpDwhGCQnFU8m/RfSiyXwuMjZe57N4YHN1FXZY4LgVW1PgZxD5UxfpIzlCG9zvtsZ4lcIHCL+BlWRksxem5B01HsETFHpcEBusTe2UhbmK3+w3LAoH1tZPLDPH79xbDTY6xo9+Dg6YB9VRIPAcTOUx0B4bqldMKsOHzwdl21ARFBcQIDAQAB",
                //"MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAkis9ObtG/59VYS05oJBeKZU0SxLYhbut3fGJJLnV4Uh+MOtQQDobRDxXTJ2S/mQ7TJXZ7OzkHgYis/+cT/3XbSZCHdyRZJ4UfQnBukHq/m1X8NWbjSoMRP6gp4lobm2pBNZsYgKodYGMEfEIIvgo5kCjg5HZyRYvBth191I2hKQBA5PTwb6kcySDzgtXB3rNuHtP6oaOS4BLseHyxTEFIq5zx9QEB/QG6fwiseP3EzbL8Z3b5vGgJyNcYeYGVBeecQ+rmvcqjCH0gVaYGIdmm59a0rZK+D11ZtmWlbAeyinj0ucNuIvkvM//ECwdQAbVuT2n32E9uPmQZZP/PZo59QIDAQAB",


        );

    }
}