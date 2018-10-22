<?php
class EmptyAction extends BaseAction
{
    public function _empty()
    {
        return outMessage(-1, '空，来自不明IP：' . get_client_ip());
    }
}