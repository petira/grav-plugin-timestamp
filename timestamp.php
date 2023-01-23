<?php
namespace Grav\Plugin;
use Grav\Common\Plugin;
use Grav\Common\Grav;
use RocketTheme\Toolbox\Event\Event;
class TimestampPlugin extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'onPagesInitialized' => ['onPagesInitialized', 0]
        ];
    }
    public function createDateValue($page, $default, $custom, $original) {
        if ($custom) {
            foreach ($custom as $variable => $value) {
                if ($variable == $original) {
                    $default = $value;
                    break;
                }
            }
        }
        $default = strtolower($default);
        switch ($default) {
            case 'date':
                $date = $page->date();
                break;
            case 'modified':
                $date = $page->modified();
                break;
            case 'past':
                $date = '0000000000';
                break;
            case 'present':
                $date = date('Y/m/d H:i:s');
                $date = strtotime($date);
                break;
            case 'future':
                $date = '2147483647';
                break;
            default:
                $date = null;
        }
        return $date;
    }
    public function pageHeaderModifier($page, $new, $date) {
        if (preg_match('/^[A-Za-z_]+[A-Za-z0-9_]+\\.[A-Za-z0-9_]+\\.[A-Za-z0-9_]+$/', $new)) {
            $new_array = explode('.', $new);
            $new0 = $new_array[0];
            $new1 = $new_array[1];
            $new2 = $new_array[2];
            $page->header()->$new0[$new1][$new2] = $date;
        } elseif (preg_match('/^[A-Za-z_]+[A-Za-z0-9_]+\\.[A-Za-z0-9_]+$/', $new)) {
            $new_array = explode('.', $new);
            $new0 = $new_array[0];
            $new1 = $new_array[1];
            $page->header()->$new0[$new1] = $date;
        } else {
            $page->header()->$new = $date;
        }
    }
    public function onPagesInitialized(Event $event)
    {
        $page = Grav::instance()['page'];
        $collection = $page->evaluate(['@root.descendants' => '']);
        $default = $this->grav['config']->get('plugins.timestamp.default');
        $custom = $this->grav['config']->get('plugins.timestamp.custom');
        $list = $this->grav['config']->get('plugins.timestamp.list');
        if (!$list) {
            return;
        }
        foreach ($list as $original => $new) {
            if (!$new) {
                $new = $original;
            }
            if (preg_match('/^[A-Za-z_]+[A-Za-z0-9_]+\\.[A-Za-z0-9_]+\\.[A-Za-z0-9_]+$/', $original)) {
                $original_array = explode('.', $original);
                $original0 = $original_array[0];
                $original1 = $original_array[1];
                $original2 = $original_array[2];
                foreach ($collection as $page) {
                    if (isset($page->header()->$original0[$original1][$original2])) {
                        $date = $page->header()->$original0[$original1][$original2];
                        $date = strtotime($date);
                    } else {
                        $date = self::createDateValue($page, $default, $custom, $original);
                    }
                    self::pageHeaderModifier($page, $new, $date);
                }
            } elseif (preg_match('/^[A-Za-z_]+[A-Za-z0-9_]+\\.[A-Za-z0-9_]+$/', $original)) {
                $original_array = explode('.', $original);
                $original0 = $original_array[0];
                $original1 = $original_array[1];
                foreach ($collection as $page) {
                    if (isset($page->header()->$original0[$original1])) {
                        $date = $page->header()->$original0[$original1];
                        $date = strtotime($date);
                    } else {
                        $date = self::createDateValue($page, $default, $custom, $original);
                    }
                    self::pageHeaderModifier($page, $new, $date);
                }
            } else {
                foreach ($collection as $page) {
                    if (isset($page->header()->$original)) {
                        $date = $page->header()->$original;
                        $date = strtotime($date);
                    } else {
                        $date = self::createDateValue($page, $default, $custom, $original);
                    }
                    self::pageHeaderModifier($page, $new, $date);
                }
            }
        }
    }
}
