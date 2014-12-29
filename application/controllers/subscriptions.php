<?php

/**
 * Class Subscriptions
 * ^ this class holds SETTINGS & STATIC methods (special for subscriptions)
 */
class Subscriptions
{
    /*
     * 'GLOBAL' SETS:
     *  _tax => percentage tax
     *  _month_days => the number of days that equals to a month
     *  _plan_prices => contains array with plans per service, except the free ones.
     *  _plan_limits => number of links/words the user is limited too depending on the subscription plan
     * */

    public static
        $_tax = 19,
        $_month_days = 28,
        $_service_prices = array(
        'ranktracker' => array(
            'pro' => 99,
            'enterprise' => 299,
            'starter' => 0,
        ),
        'seocrawl' => array(
            'starter' => 99,
            'pro' => 249,
            'enterprise' => 399,
            'free' => 0,
        ),
    ),
        $_service_limits = array(
        'ranktracker' => array(
            'pro' => array(
                'number' => '10000',
                'text' => '10,000',
            ),
            'enterprise' => array(
                'number' => NULL,
                'text' => 'Unlimited',
            ),
            'starter' => array(
                'number' => '30',
                'text' => '30',
            ), // not-paid
        ),
        'seocrawl' => array(
            'starter' => array(
                'number' => '35000',
                'text' => '35,000',
            ),
            'pro' => array(
                'number' => '250000',
                'text' => '250,000',
            ),
            'enterprise' => array(
                'number' => '1000000',
                'text' => '1 Million',
            ),
            'free' => array(
                'number' => '30',
                'text' => '30',
            ), // not-paid
        ),
    );

    /**
     * @param $plan
     * @param $service
     * @return array
     */
    public static function getDefaultForAdmin($service)
    {
        $plan = 'enterprise';
        return array(
            'plan' => $plan,
            'payment_type' => 'manual',
            'limit' => self::$_service_limits[$service][$plan]['text'],
            'months' => 12,
            'started_on' => date('Y-m-d'),
            'status' => 'approved',
            'main_status' => 'approved',
        );
    }

    /**
     * @param $service
     * @return array
     */
    public static function getDefaultNotSubscribed($service)
    {
        $plan = ($service == 'ranktracker') ? 'starter' : 'free';
        return array(
            'plan' => $plan,
            'payment_type' => 'none',
            'limit' => self::$_service_limits[$service][$plan]['text'],
            'months' => 0,
            'started_on' => date('Y-m-d'),
            'status' => 0,
            'main_status' => 0,
        );
    }

    /**
     * @param $timestamp
     * @return int
     */
    public static function isExpired($timestamp)
    {
        return (int)(self::getCurrentTimestamp() > $timestamp);
    }

    /**
     * @param array $info
     * @param int $extra
     * @return int
     */
    public static function getExpirationTimestamp(array $info)
    {
        $addToStartDate = '+' . ($info['months'] * self::$_month_days) . ' days';
        return self::getNewTimestamp($info['started_on'], $addToStartDate);
    }

    /**
     * @param $date
     * @param string $plus
     * @return int
     */
    public static function getNewTimestamp($date, $plus = '')
    {
        if($plus !== '')  {
            return strtotime($plus, strtotime($date));
        }

        return strtotime($date);
    }

    /**
     * @return int
     */
    public static function getCurrentTimestamp()
    {
        return strtotime(date('Y-m-d', time()));
    }

    /**
     * @param $amount
     * @return float
     */
    public static function addTaxes($amount) {
        return round((self::$_tax / 100 * $amount), 2);
    }

    /**
     * @param array $subscription
     * @param bool $VAT
     * @return string
     */
    public static function getPaidAmount(array $subscription, $VAT = true)
    {
        $service = $subscription['service'];
        $plan = $subscription['plan'];
        $months = $subscription['months'];

        $amount = self::$_service_prices[$service][$plan] * $months;
        if ($VAT) {
            $amount += self::addTaxes($amount);
        }

        return number_format($amount, 2);
    }

    /**
     * @return int
     */
    public static function oneDay()
    {
        return 60 * 60 * 24;
    }

    /**
     * @param array $subscription
     * @return int
     */
    public static function getDaysPassed(array $subscription)
    {
        return ceil((self::getCurrentTimestamp() - self::getNewTimestamp($subscription['started_on'])) / self::oneDay());
    }

    /**
     * @param array $subscription
     * @return string
     */
    public static function getUsedAmount(array $subscription)
    {
        $amount = self::getDaysPassed($subscription) * self::$_service_prices[$subscription['service']][$subscription['plan']] / self::$_month_days;
        return round($amount,2);
    }

    /**
     * @param Subscriptions_Model $subscription
     * @param $userInfo
     * @param $service
     * @return array
     */
    public static function getServiceSubscription(Subscriptions_Model $subscription, $userInfo, $service)
    {
        $info = $subscription->getSubscriptionInfo($userInfo['id'], $service);

        // handle 'no subscription found'
        if (!is_array($info)) {
            $info = ($userInfo['userRole'] == 'admin') ? self::getDefaultForAdmin($service) : self::getDefaultNotSubscribed($service);
        }

        // save status:
        $main_status = $info['status'];

        // handle 'expired'
        $tempExpired = self::isExpired(self::getExpirationTimestamp($info));

        // default to 'free' / 'starter' IF subscription is different than 'approved' / had 'expired':
        if ($tempExpired OR $info['status'] !== 'approved') {
            $info = self::getDefaultNotSubscribed($service);
        }

        // handle 'extra information':
        $info['expires_on'] = self::getExpirationTimestamp($info);
        $info['expired'] = $tempExpired;
        $info['crawl_limit'] = self::$_service_limits[$service][$info['plan']]['text'];
        $info['crawl_limit_no'] = self::$_service_limits[$service][$info['plan']]['number'];
        $info['main_status'] = $main_status;

        return $info;
    }

    /**
     * @param $service
     * @param $plan
     * @return int
     */
    public static function isPaid($service, $plan)
    {
        if (!isset(self::$_service_prices[strtolower($service)][strtolower($plan)])) {
            return 0;
        }

        return (int)(self::$_service_prices[strtolower($service)][strtolower($plan)] > 0);
    }

    /**
     * @param $service
     * @param $currentPlan
     * @param $newPlan
     * @return string (renewal / none / extension / upgrade / downgrade)
     */
    public static function getOperation($service, $currentSubscription, $newPlan)
    {
        $service = strtolower($service);
        $currentAmount = self::$_service_prices[$service][strtolower($currentSubscription['plan'])];
        $newAmount = self::$_service_prices[$service][strtolower($newPlan)];

        // 1 -> check if any change to subscription and if it's unpaid (= $0)
        if (($newAmount == $currentAmount) AND $newAmount == 0) {
            return 'none';
        }

        // 2 -> check if expired:
        if ($currentSubscription['expired']) {
            return 'renewal';
        }

        // 3 -> check if it's the same subscription based on amount
        if ($newAmount == $currentAmount) {
            return 'extension';
        }

        // 4 -> determine if he asks for an upgrade OR downgrade by checking if the new amount is bigger or lower then the current one.
        return ($newAmount > $currentAmount) ? 'upgrade' : 'downgrade';
    }
}