<?php

/**
 * Created by PhpStorm.
 * User: AD
 * Date: 06/06/24
 * Time: 2:55 pm
 */


use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;


/**
 * Get Full name from user
 * @param null $userModel
 * @return string
 */
function getFullName($userModel = null): string
{
    if ($userModel) {
        if (is_array($userModel) && ($userModel['first_name'] || $userModel['last_name'])) {
            return $userModel['first_name'] . ' ' . $userModel['last_name'];
        }
        if ($userModel->first_name || $userModel->last_name) {
            return $userModel->first_name . ' ' . $userModel->last_name;
        } else {
            return 'N/a';
        }
    }
    if (Auth::user()) {
        return Auth::user()->first_name . ' ' . Auth::user()->last_name;
    }
    return 'N/a';
}


/**
 * Validate email address
 * @param $email
 * @return bool
 */
function isValidEmail($email): bool
{
    $validator = Validator::make(['email' => $email], ['email' => 'required|email']);
    if ($validator->fails()) {
        return false;
    }
    return true;
}

function sqlDate($date = null)
{
    if ($date) {
        return date('Y-m-d H:i:s', strtotime($date));
    }
    return date('Y-m-d H:i:s');
}


/**
 * @param $date
 * @param string $format
 * @return false|string
 */
function convertDate($date, string $format = 'd/m/Y')
{
    if ($date) {
        return date($format, strtotime($date));
    }
    return 'N/A';
}


function convertDateTime($date, $format = 'd-m-Y H:i:s')
{
    return date($format, strtotime($date));
}


/**
 * Format number into currency structure
 * @param $amount
 * @param int $decimal
 * @param string $decimalSeparator
 * @param string $thousandSeparator
 * @return string
 */
function currencyFormat($amount, int $decimal = 2, string $decimalSeparator = '.', string $thousandSeparator = ','): string
{
    if ($amount == 0) {
        return '0.0';
    }
    return preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $amount);
}

function printData($data): void
{
    echo '<pre>';
    print_r($data);
    echo "</pre>";
}


/**
 * @param $exception
 * @param bool $shouldShowError
 * @param string $name
 * @return string
 */
function handleExceptionMessage($exception, bool $shouldShowError = false, string $name = "resource"): string
{
    $errorMessage = "Something unexpected happened. " . $shouldShowError ? $exception->getMessage() : "";
    if ($exception instanceof ModelNotFoundException) {
        $errorMessage = "Requested " . $name . " is not available. Please try again later.";
    } elseif ($exception instanceof QueryException) {
        $errorCode = $exception->getCode();
        if ($errorCode == 23000) {
            //$errorMessage = ($name ? $name : "This ") . "is being used and can not be deleted";
            $errorMessage = "Integrity constraint violation.";
        }
    }
    Log::error($exception);
    return $errorMessage;
}


function isActiveRoute($routeName, $subRoot = false, $param = [], $className = "active")
{
    if (is_array($routeName)) {
        $hasRoute = false;
        foreach ($routeName as $name) {
            $route = trim(route($name, $param, false), '/');
            if ($subRoot) {
                $route = $route . '*';
            }
            if (Request::is($route)) {
                $hasRoute = true;
            }
        }
        return $hasRoute ? $className : null;
    } else {
        $route = trim(route($routeName, $param, false), '/');
        if ($subRoot) {
            $route = $route . '*';
        }
        return Request::is($route) ? $className : null;
    }
}


/**
 * Generate uid
 * @param string $param
 * @return string
 */
function generateUid(string $param = ""): string
{
    return md5(date("Y-m-d h:i:sa") . rand(99999, 29839999) . $param);
}


/**
 * @param int $length
 * @return string
 */
function generateSmallId(int $length = 5): string
{
    $string = generateUid('J39SJE7');
    return strtoupper(substr($string, 0, $length));
}

/**
 * Check if the value is selected from the Request
 * @param $field
 * @param $value
 * @return string
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function isSelectedFromReq($field, $value): string
{
    return request()->has($field) && request()->get($field) == $value ? "selected" : "";
}


function isDevelopmentServer(): bool
{
    return !app()->isProduction();
}


/**
 * Get Roles access level badge
 * @param $level
 * @param null $slug
 * @param int $adminLevel
 * @return string
 */
function getAccessLevelBadge($level, $slug = null, int $adminLevel = 8): string
{
    $levelBadge = ' <span class="badge">Unverified</span>';

    if ($level >= $adminLevel) {
        if ($level == 15) {
            $levelBadge = '<span class="badge bg-red-lt">Super Admin</span>';
        } else {
            $levelBadge = '<span class="badge bg-orange-lt">Admin Access</span>';
        }
    } else {
        switch ($level) {
            case 1:
                $levelBadge = '<span class="badge bg-teal text-teal-fg">Users</span>';
                break;
            default:
                break;
        }
    }
    return $levelBadge;
}


function getMethodBadge($methodType): string
{
    $methodClass = match (strtolower($methodType)) {
        'put', 'post' => 'warning',
        'delete' => 'danger',
        default => 'info',
    };
    return 'badge-' . $methodClass;
}

function getActiveStatusBadge($status, $icon = true): string
{
    if ($status) {
        $statusBadge = $icon ? '<i class="ti ti-circle-check-filled text-success"></i>' : '<span class="badge badge-sm">Active</span>';
    } else {
        $statusBadge = $icon ? '<i class="ti ti-circle-x-filled text-danger"></i>' : '<span class="badge badge-sm">Inactive</span>';
    }
    return $statusBadge;
}

function getStatusBadge($status, $additionalClass = "", $light = false, $statusLabel = null)
{

    $class = "bg-default";
    $label = str_replace('_', ' ', ucfirst($status));

    switch ($status) {
        case "completed":
            $class = $light ? 'bg-success-lt' : 'bg-success text-success-fg';
            break;
        case "pending":
            $class = $light ? 'bg-warning-lt ' : 'bg-warning text-warning-fg';
            break;

        case "in_progress":
            $class = $light ? 'bg-yellow-lt' : 'bg-yellow text-success-fg';
            break;
        default:
            break;
    }
    $label = $statusLabel ? $statusLabel : $label;
    return ' <div class="badge ' . $class . ' fw-normal px-2 py-1 ' . $additionalClass . '"> ' . $label . ' </div>';
}

function getStatusText($status, $additionalClass = "", $statusLabel = null,$onlyClass=false)
{

    $class = "";
    $label = str_replace('_', ' ', ucfirst($status));

    switch ($status) {
        case "active":
        case "approved":
        case "completed":
            $class = 'text-success';
            break;
        case "pending":
        case "withdrawn":
        case "refunded":
            $class = 'text-warning';
            break;

        case "processing":
            $class = 'text-azure';
            break;
        case "dispatched":
            $class = 'text-green';
            break;

        case "reject":
        case "cancelled":
        case "inactive":
        case "rejected":
        case "failed":
        case "cancelled":
        case "auto_cancelled":
            $class = 'text-red';
            break;
        default:
            break;
    }
    if ($onlyClass){
        return $class;
    }
    $label = $statusLabel ? $statusLabel : $label;
    return ' <span class="'. $class .' ' . $additionalClass . '"> ' . $label . ' </div>';
}

function getTaskStatusBadge($status, $additionalClass = ""): string
{
    switch ($status) {
        case "pending":
            $statusBadge = '<i class="ti ti-clipboard-text text-danger fs-3 ' . $additionalClass . '"></i>';
            break;
        case "in_progress":
            $statusBadge = '<i class="ti ti-circle-check text-danger fs-3 ' . $additionalClass . '"></i>';
            break;
        case "completed":
            $statusBadge = '<i class="ti ti-map-pin text-danger fs-3 ' . $additionalClass . '"></i>';
            break;
      
        default:
            $statusBadge = '<i class="ti ti-user text-danger fs-3 ' . $additionalClass . '"></i>';
            break;
    }

    return $statusBadge;
}

function getPriorityBadge($priority, $additionalClass = "", $light = false, $statusLabel = null)
{
    $class = "bg-default";
    $label = str_replace('_', ' ', ucfirst($priority));

    switch ($priority) {
        case "low":
            $class = $light ? 'bg-yellow-lt' : 'bg-yellow text-success-fg';
            break;
        case "medium":
            $class = $light ? 'bg-warning-lt' : 'bg-warning text-warning-fg';
            break;
        case "high":
            $class = $light ? 'bg-danger-lt' : 'bg-danger text-warning-fg';
            break;
        default:
            break;
    }

    $label = $statusLabel ? $statusLabel : $label;
    return '<div class="badge ' . $class . ' fw-normal px-2 py-1 ' . $additionalClass . '"> ' . $label . ' </div>';
}

/**
 * Handle file upload and remove
 * of existing file
 * @param $file
 * @param string $path
 * @param $existingFile
 * @return false|string
 */
function handleFileUpload($file, string $path = '', $existingFile = null): false|string
{
    $filename = generateUid() . '.' . $file->getClientOriginalExtension();
    $newPath = Storage::disk('public_upload')->putFileAs($path, $file, $filename);
    removeImage($existingFile);
    return 'images' . '/' . $newPath;
}

function removeImage($existingFile)
{
    if ($existingFile && Storage::disk('public_upload')->exists($existingFile)) {
        Storage::disk('public_upload')->delete($existingFile);
    }
}

function getDiscountPercent($product)
{
    $percent = 0;
    return round((($product->display_price - $product->discount_value) / $product->display_price) * 100);
}

function isExistInReqParams($field, $value)
{
    if (request()->has($field)) {
        $param = explode(',', request()->get($field));
        if (in_array($value, $param)) {
            return true;
        }
    }
    return false;
}

function formUrlBasedOnRequest($field, $value, $multi = false)
{
    $queryParams = request()->query();
    if ($multi) {
        if (isset($queryParams[$field])) {
            $param = explode(',', $queryParams[$field]);
            if (in_array($value, $param)) {
                $param = array_diff($param, [$value]);
            } else {
                $param[] = $value;
            }
            $queryParams[$field] = implode(',', $param);
        } else {
            $queryParams[$field] = $value;
        }
    } else {
        $queryParams[$field] = $value;
    }

    $queryParams['page'] = 1;
    return url()->current() . '?' . http_build_query($queryParams);
}

function getSelectedValueLabel($field, $data, $label = "label", $value = 'value')
{
    $requestValue = request()->get($field);
    $values = array_column($data, $value);
    $index = array_search($requestValue, $values);
    return $index !== false ? ($data[$index][$label] ?? null) : null;
}


function urlExceptParams($keys)
{
    return url()->query(url()->current(), request()->except($keys));
}

function formatAddress($address)
{
    $sequence = ['house_number', 'locality', 'address', 'city', 'state', 'country', 'pincode'];
    $filteredData = array_filter(array_map(function ($key) use ($address) {
        return $address[$key] ?? null;
    }, $sequence));
    return implode(', ', $filteredData);
}

function generalExportHeaderStyle()
{
    return [
        'row' => [
            1 => [
                'font' => [
                    'alignment' => ['vertical' => 'center'],
                    'color' => [
                        'rgb' => 'FFFFFF'
                    ]
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => "0a5cc0"],
                ],
                'height' => 50
            ]
        ]
    ];
}
