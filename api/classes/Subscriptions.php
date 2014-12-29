<?php
/**
 * !!! DUPLICATED AFTER /controllers/subscriptions.php - but that one has more stuff going on !!!
 * Class Subscriptions
 * ^ this class holds SETTINGS & STATIC methods! for subscriptions
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
            'limit' => Subscriptions::$_service_limits[$service][$plan]['text'],
            'months' => 12,
            'started_on' => date('Y-m-d'),
            'status' => 'approved',
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
            'limit' => Subscriptions::$_service_limits[$service][$plan]['text'],
            'months' => 0,
            'started_on' => date('Y-m-d'),
            'status' => 0,
        );
    }

    public static function isExpired($timestamp) {
        return (int)(self::getCurrentTimestamp() > $timestamp);
    }

    /**
     * @param $info
     * @return int
     */
    public static function getExpirationTimestamp($info) {
        $addToStartDate = '+' . ($info['months'] * Subscriptions::$_month_days) . ' days';
        return Subscriptions::getNewTimestamp($info['started_on'], $addToStartDate);
    }

    /**
     * Helper functions:
     * getNewTimetamp + getCurrentTimestamp;
     */

    /**
     * @param $date
     * @param $plus
     * @return int
     */
    public static function getNewTimestamp($date, $plus)
    {
        return strtotime($plus, strtotime($date));
    }

    /**
     * @return int
     */
    public static function getCurrentTimestamp()
    {
        return strtotime(date('Y-m-d', time()));
    }

    /**
     * @param $service
     * @param $plan
     * @param $months
     * @return float
     */
    public static function getPaidAmount($service, $plan, $months) {
        $amount = self::$_service_prices[$service][$plan] * $months;
        $amount += 19/100*$amount;
        return $amount;
    }
}