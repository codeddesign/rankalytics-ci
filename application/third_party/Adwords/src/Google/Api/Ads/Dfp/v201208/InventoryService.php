<?php
/**
 * Contains all client objects for the InventoryService
 * service.
 *
 * PHP version 5
 *
 * Copyright 2014, Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package    GoogleApiAdsDfp
 * @subpackage v201208
 * @category   WebServices
 * @copyright  2014, Google Inc. All Rights Reserved.
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License,
 *             Version 2.0
 */

/** Required classes. **/
require_once "Google/Api/Ads/Dfp/Lib/DfpSoapClient.php";

if (!class_exists("AdSenseSettings", FALSE)) {
/**
 * Contains the AdSense configuration for an {@link AdUnit}.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdSenseSettings {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdSenseSettings";

  /**
   * @access public
   * @var boolean
   */
  public $adSenseEnabled;

  /**
   * @access public
   * @var string
   */
  public $borderColor;

  /**
   * @access public
   * @var string
   */
  public $titleColor;

  /**
   * @access public
   * @var string
   */
  public $backgroundColor;

  /**
   * @access public
   * @var string
   */
  public $textColor;

  /**
   * @access public
   * @var string
   */
  public $urlColor;

  /**
   * @access public
   * @var tnsAdSenseSettingsAdType
   */
  public $adType;

  /**
   * @access public
   * @var tnsAdSenseSettingsBorderStyle
   */
  public $borderStyle;

  /**
   * @access public
   * @var tnsAdSenseSettingsFontFamily
   */
  public $fontFamily;

  /**
   * @access public
   * @var tnsAdSenseSettingsFontSize
   */
  public $fontSize;

  /**
   * @access public
   * @var Size_StringMapEntry[]
   */
  public $afcFormats;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($adSenseEnabled = null, $borderColor = null, $titleColor = null, $backgroundColor = null, $textColor = null, $urlColor = null, $adType = null, $borderStyle = null, $fontFamily = null, $fontSize = null, $afcFormats = null) {
    $this->adSenseEnabled = $adSenseEnabled;
    $this->borderColor = $borderColor;
    $this->titleColor = $titleColor;
    $this->backgroundColor = $backgroundColor;
    $this->textColor = $textColor;
    $this->urlColor = $urlColor;
    $this->adType = $adType;
    $this->borderStyle = $borderStyle;
    $this->fontFamily = $fontFamily;
    $this->fontSize = $fontSize;
    $this->afcFormats = $afcFormats;
  }

}}

if (!class_exists("AdSenseSettingsInheritedProperty", FALSE)) {
/**
 * The property of the AdUnit that specifies how and from where the
 * AdSenseSettings are inherited.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdSenseSettingsInheritedProperty {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdSenseSettingsInheritedProperty";

  /**
   * @access public
   * @var AdSenseSettings
   */
  public $value;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($value = null) {
    $this->value = $value;
  }

}}

if (!class_exists("AdUnitAction", FALSE)) {
/**
 * Represents the actions that can be performed on {@link AdUnit} objects.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdUnitAction {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdUnitAction";

  /**
   * @access public
   * @var string
   */
  public $AdUnitActionType;
  private $_parameterMap = array(
    "AdUnitAction.Type" => "AdUnitActionType",
  );

  /**
   * Provided for setting non-php-standard named variables
   * @param $var Variable name to set
   * @param $value Value to set
   */
  public function __set($var, $value) {
    $this->{$this->_parameterMap[$var]} = $value;
  }

  /**
   * Provided for getting non-php-standard named variables
   * @param $var Variable name to get
   * @return mixed Variable value
   */
  public function __get($var) {
    if (!isset($this->_parameterMap[$var])) {
      return null;
    }
    return $this->{$this->_parameterMap[$var]};
  }

  /**
   * Provided for getting non-php-standard named variables
   * @return array parameter map
   */
  protected function getParameterMap() {
    return $this->_parameterMap;
  }

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($AdUnitActionType = null) {
    $this->AdUnitActionType = $AdUnitActionType;
  }

}}

if (!class_exists("AdUnit", FALSE)) {
/**
 * An {@code AdUnit} represents a chunk of identified inventory for the
 * publisher. It contains all the settings that need to be associated with
 * inventory in order to serve ads to it. An {@code AdUnit} can also be the
 * parent of other ad units in the inventory hierarchy.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdUnit {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdUnit";

  /**
   * @access public
   * @var string
   */
  public $id;

  /**
   * @access public
   * @var string
   */
  public $parentId;

  /**
   * @access public
   * @var boolean
   */
  public $hasChildren;

  /**
   * @access public
   * @var AdUnitParent[]
   */
  public $parentPath;

  /**
   * @access public
   * @var string
   */
  public $name;

  /**
   * @access public
   * @var string
   */
  public $description;

  /**
   * @access public
   * @var tnsAdUnitTargetWindow
   */
  public $targetWindow;

  /**
   * @access public
   * @var tnsInventoryStatus
   */
  public $status;

  /**
   * @access public
   * @var string
   */
  public $adUnitCode;

  /**
   * @access public
   * @var AdUnitSize[]
   */
  public $adUnitSizes;

  /**
   * @access public
   * @var tnsTargetPlatform
   */
  public $targetPlatform;

  /**
   * @access public
   * @var tnsMobilePlatform
   */
  public $mobilePlatform;

  /**
   * @access public
   * @var boolean
   */
  public $explicitlyTargeted;

  /**
   * @access public
   * @var AdSenseSettingsInheritedProperty
   */
  public $inheritedAdSenseSettings;

  /**
   * @access public
   * @var LabelFrequencyCap[]
   */
  public $appliedLabelFrequencyCaps;

  /**
   * @access public
   * @var LabelFrequencyCap[]
   */
  public $effectiveLabelFrequencyCaps;

  /**
   * @access public
   * @var integer[]
   */
  public $effectiveTeamIds;

  /**
   * @access public
   * @var integer[]
   */
  public $appliedTeamIds;

  /**
   * @access public
   * @var DateTime
   */
  public $lastModifiedDateTime;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($id = null, $parentId = null, $hasChildren = null, $parentPath = null, $name = null, $description = null, $targetWindow = null, $status = null, $adUnitCode = null, $adUnitSizes = null, $targetPlatform = null, $mobilePlatform = null, $explicitlyTargeted = null, $inheritedAdSenseSettings = null, $appliedLabelFrequencyCaps = null, $effectiveLabelFrequencyCaps = null, $effectiveTeamIds = null, $appliedTeamIds = null, $lastModifiedDateTime = null) {
    $this->id = $id;
    $this->parentId = $parentId;
    $this->hasChildren = $hasChildren;
    $this->parentPath = $parentPath;
    $this->name = $name;
    $this->description = $description;
    $this->targetWindow = $targetWindow;
    $this->status = $status;
    $this->adUnitCode = $adUnitCode;
    $this->adUnitSizes = $adUnitSizes;
    $this->targetPlatform = $targetPlatform;
    $this->mobilePlatform = $mobilePlatform;
    $this->explicitlyTargeted = $explicitlyTargeted;
    $this->inheritedAdSenseSettings = $inheritedAdSenseSettings;
    $this->appliedLabelFrequencyCaps = $appliedLabelFrequencyCaps;
    $this->effectiveLabelFrequencyCaps = $effectiveLabelFrequencyCaps;
    $this->effectiveTeamIds = $effectiveTeamIds;
    $this->appliedTeamIds = $appliedTeamIds;
    $this->lastModifiedDateTime = $lastModifiedDateTime;
  }

}}

if (!class_exists("AdUnitPage", FALSE)) {
/**
 * Captures a page of {@link AdUnit} objects.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdUnitPage {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdUnitPage";

  /**
   * @access public
   * @var integer
   */
  public $totalResultSetSize;

  /**
   * @access public
   * @var integer
   */
  public $startIndex;

  /**
   * @access public
   * @var AdUnit[]
   */
  public $results;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($totalResultSetSize = null, $startIndex = null, $results = null) {
    $this->totalResultSetSize = $totalResultSetSize;
    $this->startIndex = $startIndex;
    $this->results = $results;
  }

}}

if (!class_exists("AdUnitParent", FALSE)) {
/**
 * The summary of a parent {@link AdUnit}.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdUnitParent {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdUnitParent";

  /**
   * @access public
   * @var string
   */
  public $id;

  /**
   * @access public
   * @var string
   */
  public $name;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($id = null, $name = null) {
    $this->id = $id;
    $this->name = $name;
  }

}}

if (!class_exists("ApiError", FALSE)) {
/**
 * The API error base class that provides details about an error that occurred
 * while processing a service request.
 * 
 * <p>The OGNL field path is provided for parsers to identify the request data
 * element that may have caused the error.</p>
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "ApiError";

  /**
   * @access public
   * @var string
   */
  public $fieldPath;

  /**
   * @access public
   * @var string
   */
  public $trigger;

  /**
   * @access public
   * @var string
   */
  public $errorString;

  /**
   * @access public
   * @var string
   */
  public $ApiErrorType;
  private $_parameterMap = array(
    "ApiError.Type" => "ApiErrorType",
  );

  /**
   * Provided for setting non-php-standard named variables
   * @param $var Variable name to set
   * @param $value Value to set
   */
  public function __set($var, $value) {
    $this->{$this->_parameterMap[$var]} = $value;
  }

  /**
   * Provided for getting non-php-standard named variables
   * @param $var Variable name to get
   * @return mixed Variable value
   */
  public function __get($var) {
    if (!isset($this->_parameterMap[$var])) {
      return null;
    }
    return $this->{$this->_parameterMap[$var]};
  }

  /**
   * Provided for getting non-php-standard named variables
   * @return array parameter map
   */
  protected function getParameterMap() {
    return $this->_parameterMap;
  }

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("ApiVersionError", FALSE)) {
/**
 * Errors related to the usage of API versions.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class ApiVersionError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "ApiVersionError";

  /**
   * @access public
   * @var tnsApiVersionErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("ApplicationException", FALSE)) {
/**
 * Base class for exceptions.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class ApplicationException {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "ApplicationException";

  /**
   * @access public
   * @var string
   */
  public $message;

  /**
   * @access public
   * @var string
   */
  public $ApplicationExceptionType;
  private $_parameterMap = array(
    "ApplicationException.Type" => "ApplicationExceptionType",
  );

  /**
   * Provided for setting non-php-standard named variables
   * @param $var Variable name to set
   * @param $value Value to set
   */
  public function __set($var, $value) {
    $this->{$this->_parameterMap[$var]} = $value;
  }

  /**
   * Provided for getting non-php-standard named variables
   * @param $var Variable name to get
   * @return mixed Variable value
   */
  public function __get($var) {
    if (!isset($this->_parameterMap[$var])) {
      return null;
    }
    return $this->{$this->_parameterMap[$var]};
  }

  /**
   * Provided for getting non-php-standard named variables
   * @return array parameter map
   */
  protected function getParameterMap() {
    return $this->_parameterMap;
  }

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($message = null, $ApplicationExceptionType = null) {
    $this->message = $message;
    $this->ApplicationExceptionType = $ApplicationExceptionType;
  }

}}

if (!class_exists("ArchiveAdUnits", FALSE)) {
/**
 * The action used for archiving {@link AdUnit} objects.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class ArchiveAdUnits extends AdUnitAction {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "ArchiveAdUnits";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($AdUnitActionType = null) {
    parent::__construct();
    $this->AdUnitActionType = $AdUnitActionType;
  }

}}

if (!class_exists("AssignAdUnitsToPlacement", FALSE)) {
/**
 * The action used for assigning a group of {@link AdUnit} objects to a
 * {@link Placement}.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AssignAdUnitsToPlacement extends AdUnitAction {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AssignAdUnitsToPlacement";

  /**
   * @access public
   * @var integer
   */
  public $placementId;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($placementId = null, $AdUnitActionType = null) {
    parent::__construct();
    $this->placementId = $placementId;
    $this->AdUnitActionType = $AdUnitActionType;
  }

}}

if (!class_exists("Authentication", FALSE)) {
/**
 * A representation of the authentication protocols that can be used.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class Authentication {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "Authentication";

  /**
   * @access public
   * @var string
   */
  public $AuthenticationType;
  private $_parameterMap = array(
    "Authentication.Type" => "AuthenticationType",
  );

  /**
   * Provided for setting non-php-standard named variables
   * @param $var Variable name to set
   * @param $value Value to set
   */
  public function __set($var, $value) {
    $this->{$this->_parameterMap[$var]} = $value;
  }

  /**
   * Provided for getting non-php-standard named variables
   * @param $var Variable name to get
   * @return mixed Variable value
   */
  public function __get($var) {
    if (!isset($this->_parameterMap[$var])) {
      return null;
    }
    return $this->{$this->_parameterMap[$var]};
  }

  /**
   * Provided for getting non-php-standard named variables
   * @return array parameter map
   */
  protected function getParameterMap() {
    return $this->_parameterMap;
  }

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($AuthenticationType = null) {
    $this->AuthenticationType = $AuthenticationType;
  }

}}

if (!class_exists("AuthenticationError", FALSE)) {
/**
 * An error for an exception that occurred when authenticating.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AuthenticationError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AuthenticationError";

  /**
   * @access public
   * @var tnsAuthenticationErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("ClientLogin", FALSE)) {
/**
 * The credentials for the {@code ClientLogin} API authentication protocol.
 * 
 * See {@link http://code.google.com/apis/accounts/docs/AuthForInstalledApps.html}.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class ClientLogin extends Authentication {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "ClientLogin";

  /**
   * @access public
   * @var string
   */
  public $token;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($token = null, $AuthenticationType = null) {
    parent::__construct();
    $this->token = $token;
    $this->AuthenticationType = $AuthenticationType;
  }

}}

if (!class_exists("CommonError", FALSE)) {
/**
 * A place for common errors that can be used across services.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class CommonError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "CommonError";

  /**
   * @access public
   * @var tnsCommonErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("CreativeWrapperError", FALSE)) {
/**
 * Errors specific to creative wrappers.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class CreativeWrapperError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "CreativeWrapperError";

  /**
   * @access public
   * @var tnsCreativeWrapperErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("Date", FALSE)) {
/**
 * Represents a date.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class Date {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "Date";

  /**
   * @access public
   * @var integer
   */
  public $year;

  /**
   * @access public
   * @var integer
   */
  public $month;

  /**
   * @access public
   * @var integer
   */
  public $day;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($year = null, $month = null, $day = null) {
    $this->year = $year;
    $this->month = $month;
    $this->day = $day;
  }

}}

if (!class_exists("DfpDateTime", FALSE)) {
/**
 * Represents a date combined with the time of day.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class DfpDateTime {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "DateTime";

  /**
   * @access public
   * @var Date
   */
  public $date;

  /**
   * @access public
   * @var integer
   */
  public $hour;

  /**
   * @access public
   * @var integer
   */
  public $minute;

  /**
   * @access public
   * @var integer
   */
  public $second;

  /**
   * @access public
   * @var string
   */
  public $timeZoneID;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($date = null, $hour = null, $minute = null, $second = null, $timeZoneID = null) {
    $this->date = $date;
    $this->hour = $hour;
    $this->minute = $minute;
    $this->second = $second;
    $this->timeZoneID = $timeZoneID;
  }

}}

if (!class_exists("DeactivateAdUnits", FALSE)) {
/**
 * The action used for deactivating {@link AdUnit} objects.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class DeactivateAdUnits extends AdUnitAction {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "DeactivateAdUnits";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($AdUnitActionType = null) {
    parent::__construct();
    $this->AdUnitActionType = $AdUnitActionType;
  }

}}

if (!class_exists("EntityLimitReachedError", FALSE)) {
/**
 * An error that occurs when creating an entity if the limit on the number of allowed entities for
 * a network has already been reached.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class EntityLimitReachedError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "EntityLimitReachedError";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("FeatureError", FALSE)) {
/**
 * Errors related to feature management.  If you attempt using a feature that is not available to
 * the current network you'll receive a FeatureError with the missing feature as the trigger.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class FeatureError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "FeatureError";

  /**
   * @access public
   * @var tnsFeatureErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("FrequencyCap", FALSE)) {
/**
 * Represents a limit on the number of times a single viewer can be exposed to
 * the same {@link LineItem} in a specified time period.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class FrequencyCap {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "FrequencyCap";

  /**
   * @access public
   * @var integer
   */
  public $maxImpressions;

  /**
   * @access public
   * @var integer
   */
  public $numTimeUnits;

  /**
   * @access public
   * @var tnsTimeUnit
   */
  public $timeUnit;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($maxImpressions = null, $numTimeUnits = null, $timeUnit = null) {
    $this->maxImpressions = $maxImpressions;
    $this->numTimeUnits = $numTimeUnits;
    $this->timeUnit = $timeUnit;
  }

}}

if (!class_exists("FrequencyCapError", FALSE)) {
/**
 * Lists all errors associated with frequency caps.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class FrequencyCapError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "FrequencyCapError";

  /**
   * @access public
   * @var tnsFrequencyCapErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("InternalApiError", FALSE)) {
/**
 * Indicates that a server-side error has occured. {@code InternalApiError}s
 * are generally not the result of an invalid request or message sent by the
 * client.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class InternalApiError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "InternalApiError";

  /**
   * @access public
   * @var tnsInternalApiErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("InvalidColorError", FALSE)) {
/**
 * A list of all errors associated with a color attribute.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class InvalidColorError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "InvalidColorError";

  /**
   * @access public
   * @var tnsInvalidColorErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("InventoryUnitError", FALSE)) {
/**
 * Lists the generic errors associated with {@link AdUnit} objects.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class InventoryUnitError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "InventoryUnitError";

  /**
   * @access public
   * @var tnsInventoryUnitErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("InventoryUnitPartnerAssociationError", FALSE)) {
/**
 * Errors relating to the association of partner companies with inventory units.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class InventoryUnitPartnerAssociationError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "InventoryUnitPartnerAssociationError";

  /**
   * @access public
   * @var tnsInventoryUnitPartnerAssociationErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("AdUnitSize", FALSE)) {
/**
 * An {@code AdUnitSize} represents the size of an ad in an ad unit. Starting
 * with v201108 this also represents the environment, and companions of a
 * particular ad in an ad unit. In most cases, it is a simple size with just a
 * width and a height (sometimes representing an aspect ratio).
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdUnitSize {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdUnitSize";

  /**
   * @access public
   * @var Size
   */
  public $size;

  /**
   * @access public
   * @var tnsEnvironmentType
   */
  public $environmentType;

  /**
   * @access public
   * @var AdUnitSize[]
   */
  public $companions;

  /**
   * @access public
   * @var string
   */
  public $fullDisplayString;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($size = null, $environmentType = null, $companions = null, $fullDisplayString = null) {
    $this->size = $size;
    $this->environmentType = $environmentType;
    $this->companions = $companions;
    $this->fullDisplayString = $fullDisplayString;
  }

}}

if (!class_exists("InventoryUnitSizesError", FALSE)) {
/**
 * An error specifically for InventoryUnitSizes.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class InventoryUnitSizesError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "InventoryUnitSizesError";

  /**
   * @access public
   * @var tnsInventoryUnitSizesErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("AdUnitTypeError", FALSE)) {
/**
 * Lists the errors associated with the type of {@link AdUnit} object.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdUnitTypeError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdUnitTypeError";

  /**
   * @access public
   * @var tnsAdUnitTypeErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("LabelFrequencyCap", FALSE)) {
/**
 * A {@code LabelFrequencyCap} assigns a frequency cap to a label.  The
 * frequency cap will limit the cumulative number of impressions of any ad
 * units with this label that may be shown to a particular user over a time
 * unit.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class LabelFrequencyCap {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "LabelFrequencyCap";

  /**
   * @access public
   * @var FrequencyCap
   */
  public $frequencyCap;

  /**
   * @access public
   * @var integer
   */
  public $labelId;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($frequencyCap = null, $labelId = null) {
    $this->frequencyCap = $frequencyCap;
    $this->labelId = $labelId;
  }

}}

if (!class_exists("NotNullError", FALSE)) {
/**
 * Caused by supplying a null value for an attribute that cannot be null.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class NotNullError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "NotNullError";

  /**
   * @access public
   * @var tnsNotNullErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("NullError", FALSE)) {
/**
 * Errors associated with violation of a NOT NULL check.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class NullError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "NullError";

  /**
   * @access public
   * @var tnsNullErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("DfpOAuth", FALSE)) {
/**
 * The credentials for the {@code OAuth} authentication protocol.
 * 
 * See {@link http://oauth.net/}.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class DfpOAuth extends Authentication {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "OAuth";

  /**
   * @access public
   * @var string
   */
  public $parameters;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($parameters = null, $AuthenticationType = null) {
    parent::__construct();
    $this->parameters = $parameters;
    $this->AuthenticationType = $AuthenticationType;
  }

}}

if (!class_exists("ParseError", FALSE)) {
/**
 * Lists errors related to parsing.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class ParseError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "ParseError";

  /**
   * @access public
   * @var tnsParseErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("PermissionError", FALSE)) {
/**
 * Errors related to incorrect permission.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class PermissionError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "PermissionError";

  /**
   * @access public
   * @var tnsPermissionErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("PublisherQueryLanguageContextError", FALSE)) {
/**
 * An error that occurs while executing a PQL query contained in
 * a {@link Statement} object.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class PublisherQueryLanguageContextError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "PublisherQueryLanguageContextError";

  /**
   * @access public
   * @var tnsPublisherQueryLanguageContextErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("PublisherQueryLanguageSyntaxError", FALSE)) {
/**
 * An error that occurs while parsing a PQL query contained in a
 * {@link Statement} object.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class PublisherQueryLanguageSyntaxError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "PublisherQueryLanguageSyntaxError";

  /**
   * @access public
   * @var tnsPublisherQueryLanguageSyntaxErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("QuotaError", FALSE)) {
/**
 * Describes a client-side error on which a user is attempting
 * to perform an action to which they have no quota remaining.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class QuotaError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "QuotaError";

  /**
   * @access public
   * @var tnsQuotaErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("RegExError", FALSE)) {
/**
 * Caused by supplying a value for an object attribute that does not conform
 * to a documented valid regular expression.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class RegExError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "RegExError";

  /**
   * @access public
   * @var tnsRegExErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("RequiredCollectionError", FALSE)) {
/**
 * A list of all errors to be used for validating sizes of collections.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class RequiredCollectionError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "RequiredCollectionError";

  /**
   * @access public
   * @var tnsRequiredCollectionErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("RequiredError", FALSE)) {
/**
 * Errors due to missing required field.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class RequiredError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "RequiredError";

  /**
   * @access public
   * @var tnsRequiredErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("ServerError", FALSE)) {
/**
 * Errors related to the server.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class ServerError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "ServerError";

  /**
   * @access public
   * @var tnsServerErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("Size", FALSE)) {
/**
 * Represents the dimensions of an {@link AdUnit}, {@link LineItem} or {@link Creative}.
 * <p>
 * For interstitial size (out-of-page), {@code Size} must be 1x1.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class Size {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "Size";

  /**
   * @access public
   * @var integer
   */
  public $width;

  /**
   * @access public
   * @var integer
   */
  public $height;

  /**
   * @access public
   * @var boolean
   */
  public $isAspectRatio;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($width = null, $height = null, $isAspectRatio = null) {
    $this->width = $width;
    $this->height = $height;
    $this->isAspectRatio = $isAspectRatio;
  }

}}

if (!class_exists("Size_StringMapEntry", FALSE)) {
/**
 * This represents an entry in a map with a key of type Size
 * and value of type String.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class Size_StringMapEntry {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "Size_StringMapEntry";

  /**
   * @access public
   * @var Size
   */
  public $key;

  /**
   * @access public
   * @var string
   */
  public $value;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($key = null, $value = null) {
    $this->key = $key;
    $this->value = $value;
  }

}}

if (!class_exists("SoapRequestHeader", FALSE)) {
/**
 * Represents the SOAP request header used by API requests.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class SoapRequestHeader {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "SoapRequestHeader";

  /**
   * @access public
   * @var string
   */
  public $networkCode;

  /**
   * @access public
   * @var string
   */
  public $applicationName;

  /**
   * @access public
   * @var Authentication
   */
  public $authentication;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($networkCode = null, $applicationName = null, $authentication = null) {
    $this->networkCode = $networkCode;
    $this->applicationName = $applicationName;
    $this->authentication = $authentication;
  }

}}

if (!class_exists("SoapResponseHeader", FALSE)) {
/**
 * Represents the SOAP request header used by API responses.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class SoapResponseHeader {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "SoapResponseHeader";

  /**
   * @access public
   * @var string
   */
  public $requestId;

  /**
   * @access public
   * @var integer
   */
  public $responseTime;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($requestId = null, $responseTime = null) {
    $this->requestId = $requestId;
    $this->responseTime = $responseTime;
  }

}}

if (!class_exists("Statement", FALSE)) {
/**
 * Captures the {@code WHERE}, {@code ORDER BY} and {@code LIMIT} clauses of a
 * PQL query. Statements are typically used to retrieve objects of a predefined
 * domain type, which makes SELECT clause unnecessary.
 * <p>
 * An example query text might be {@code "WHERE status = 'ACTIVE' ORDER BY id
 * LIMIT 30"}.
 * </p>
 * <p>
 * Statements support bind variables. These are substitutes for literals
 * and can be thought of as input parameters to a PQL query.
 * </p>
 * <p>
 * An example of such a query might be {@code "WHERE id = :idValue"}.
 * </p>
 * <p>
 * Statements also support use of the LIKE keyword. This provides partial and
 * wildcard string matching.
 * </p>
 * <p>
 * An example of such a query might be {@code "WHERE name LIKE 'startswith%'"}.
 * </p>
 * If using an API version newer than V201010, the value for the variable
 * idValue must then be set with an object of type {@link Value} and is one of
 * {@link NumberValue}, {@link TextValue} or {@link BooleanValue}.
 * <p>
 * If using an API version older than or equal to V201010, the value for the
 * variable idValue must then be set with an object of type {@link Param} and is
 * one of {@link DoubleParam}, {@link LongParam} or {@link StringParam}.
 * </p>
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class Statement {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "Statement";

  /**
   * @access public
   * @var string
   */
  public $query;

  /**
   * @access public
   * @var String_ValueMapEntry[]
   */
  public $values;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($query = null, $values = null) {
    $this->query = $query;
    $this->values = $values;
  }

}}

if (!class_exists("StatementError", FALSE)) {
/**
 * An error that occurs while parsing {@link Statement} objects.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class StatementError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "StatementError";

  /**
   * @access public
   * @var tnsStatementErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("StringLengthError", FALSE)) {
/**
 * Errors for Strings which do not meet given length constraints.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class StringLengthError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "StringLengthError";

  /**
   * @access public
   * @var tnsStringLengthErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("String_ValueMapEntry", FALSE)) {
/**
 * This represents an entry in a map with a key of type String
 * and value of type Value.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class String_ValueMapEntry {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "String_ValueMapEntry";

  /**
   * @access public
   * @var string
   */
  public $key;

  /**
   * @access public
   * @var Value
   */
  public $value;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($key = null, $value = null) {
    $this->key = $key;
    $this->value = $value;
  }

}}

if (!class_exists("TeamError", FALSE)) {
/**
 * Errors related to a Team.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class TeamError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "TeamError";

  /**
   * @access public
   * @var tnsTeamErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("TypeError", FALSE)) {
/**
 * An error for a field which is an invalid type.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class TypeError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "TypeError";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("UniqueError", FALSE)) {
/**
 * An error for a field which must satisfy a uniqueness constraint
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class UniqueError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "UniqueError";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("UpdateResult", FALSE)) {
/**
 * Represents the result of performing an action on objects.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class UpdateResult {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "UpdateResult";

  /**
   * @access public
   * @var integer
   */
  public $numChanges;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($numChanges = null) {
    $this->numChanges = $numChanges;
  }

}}

if (!class_exists("Value", FALSE)) {
/**
 * {@code Value} represents a value.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class Value {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "Value";

  /**
   * @access public
   * @var string
   */
  public $ValueType;
  private $_parameterMap = array(
    "Value.Type" => "ValueType",
  );

  /**
   * Provided for setting non-php-standard named variables
   * @param $var Variable name to set
   * @param $value Value to set
   */
  public function __set($var, $value) {
    $this->{$this->_parameterMap[$var]} = $value;
  }

  /**
   * Provided for getting non-php-standard named variables
   * @param $var Variable name to get
   * @return mixed Variable value
   */
  public function __get($var) {
    if (!isset($this->_parameterMap[$var])) {
      return null;
    }
    return $this->{$this->_parameterMap[$var]};
  }

  /**
   * Provided for getting non-php-standard named variables
   * @return array parameter map
   */
  protected function getParameterMap() {
    return $this->_parameterMap;
  }

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($ValueType = null) {
    $this->ValueType = $ValueType;
  }

}}

if (!class_exists("AdSenseAccountErrorReason", FALSE)) {
/**
 * An error occured while trying to associate an AdSense account with GFP. Unable to create an
 * association with AdSense or Ad Exchange account.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdSenseAccountErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdSenseAccountError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("AdSenseSettingsAdType", FALSE)) {
/**
 * Specifies the type of ads that can be served through this {@link AdUnit}.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdSenseSettingsAdType {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdSenseSettings.AdType";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("AdSenseSettingsBorderStyle", FALSE)) {
/**
 * Describes the border of the HTML elements used to surround an ad
 * displayed by the {@link AdUnit}.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdSenseSettingsBorderStyle {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdSenseSettings.BorderStyle";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("AdSenseSettingsFontFamily", FALSE)) {
/**
 * List of all possible font families.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdSenseSettingsFontFamily {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdSenseSettings.FontFamily";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("AdSenseSettingsFontSize", FALSE)) {
/**
 * List of all possible font sizes the user can choose.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdSenseSettingsFontSize {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdSenseSettings.FontSize";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("AdUnitAfcSizeErrorReason", FALSE)) {
/**
 * The supplied Afc size is not valid.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdUnitAfcSizeErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdUnitAfcSizeError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("AdUnitCodeErrorReason", FALSE)) {
/**
 * For {@link AdUnit#adUnitCode}, only alpha-numeric characters,
 * underscores, hyphens, periods, asterisks, double quotes, back slashes,
 * forward slashes, exclamations, left angle brackets, colons and
 * parentheses are allowed.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdUnitCodeErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdUnitCodeError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("AdUnitTargetWindow", FALSE)) {
/**
 * Corresponds to an HTML link's {@code target} attribute.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdUnitTargetWindow {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdUnit.TargetWindow";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("AdUnitHierarchyErrorReason", FALSE)) {
/**
 * The depth of the {@link AdUnit} in the inventory hierarchy is greater
 * than is allowed. The maximum allowed depth is two below the effective
 * root ad unit for Premium accounts and one level below effective root ad
 * unit for Small Business accounts.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdUnitHierarchyErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdUnitHierarchyError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("ApiVersionErrorReason", FALSE)) {
/**
 * Indicates that the operation is not allowed in the version the request
 * was made in.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class ApiVersionErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "ApiVersionError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("AuthenticationErrorReason", FALSE)) {
/**
 * The SOAP message contains a request header with an ambiguous definition
 * of the authentication header fields. This means either the {@code
 * authToken} and {@code oAuthToken} fields were both null or both were
 * specified. Exactly one value should be specified with each request.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AuthenticationErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AuthenticationError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("CommonErrorReason", FALSE)) {
/**
 * Describes reasons for common errors
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class CommonErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "CommonError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("CreativeWrapperErrorReason", FALSE)) {
/**
 * The reasons for the creative wrapper error.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class CreativeWrapperErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "CreativeWrapperError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("EnvironmentType", FALSE)) {
/**
 * Enum for the valid environments in which ads can be shown.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class EnvironmentType {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "EnvironmentType";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("FeatureErrorReason", FALSE)) {
/**
 * A feature is being used that is not enabled on the current network.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class FeatureErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "FeatureError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("FrequencyCapErrorReason", FALSE)) {
/**
 * The value returned if the actual value is not exposed by the requested API version.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class FrequencyCapErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "FrequencyCapError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("InternalApiErrorReason", FALSE)) {
/**
 * The single reason for the internal API error.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class InternalApiErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "InternalApiError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("InvalidColorErrorReason", FALSE)) {
/**
 * The provided value is not a valid hexadecimal color.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class InvalidColorErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "InvalidColorError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("InventoryStatus", FALSE)) {
/**
 * Represents the status of objects that represent inventory - ad units and
 * placements.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class InventoryStatus {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "InventoryStatus";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("InventoryUnitErrorReason", FALSE)) {
/**
 * Possible reasons for the error.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class InventoryUnitErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "InventoryUnitError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("InventoryUnitPartnerAssociationErrorReason", FALSE)) {
/**
 * Partner association error reason types.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class InventoryUnitPartnerAssociationErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "InventoryUnitPartnerAssociationError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("InventoryUnitSizesErrorReason", FALSE)) {
/**
 * All possible reasons the error can be thrown.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class InventoryUnitSizesErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "InventoryUnitSizesError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("AdUnitTypeErrorReason", FALSE)) {
/**
 * Possible reasons for the error.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdUnitTypeErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdUnitTypeError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("MobilePlatform", FALSE)) {
/**
 * The platform associated with a mobile {@code AdUnit}, i.e. whether this ad unit
 * appears in a mobile application or in a mobile web site.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class MobilePlatform {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "MobilePlatform";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("NotNullErrorReason", FALSE)) {
/**
 * The reasons for the target error.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class NotNullErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "NotNullError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("NullErrorReason", FALSE)) {
/**
 * The reasons for the validation error.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class NullErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "NullError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("ParseErrorReason", FALSE)) {
/**
 * The reasons for the target error.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class ParseErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "ParseError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("PermissionErrorReason", FALSE)) {
/**
 * Describes reasons for permission errors.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class PermissionErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "PermissionError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("PublisherQueryLanguageContextErrorReason", FALSE)) {
/**
 * The reasons for the target error.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class PublisherQueryLanguageContextErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "PublisherQueryLanguageContextError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("PublisherQueryLanguageSyntaxErrorReason", FALSE)) {
/**
 * The reasons for the target error.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class PublisherQueryLanguageSyntaxErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "PublisherQueryLanguageSyntaxError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("QuotaErrorReason", FALSE)) {
/**
 * The number of requests made per second is too high and has exceeded the
 * allowable limit. The recommended approach to handle this error is to wait
 * about 5 seconds and then retry the request. Note that this does not
 * guarantee the request will succeed. If it fails again, try increasing the
 * wait time.
 * <p>
 * Another way to mitigate this error is to limit requests to 2 per second.
 * Once again this does not guarantee that every request will succeed, but
 * may help reduce the number of times you receive this error.
 * </p>
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class QuotaErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "QuotaError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("RegExErrorReason", FALSE)) {
/**
 * The reasons for the target error.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class RegExErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "RegExError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("RequiredCollectionErrorReason", FALSE)) {
/**
 * A required collection is missing.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class RequiredCollectionErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "RequiredCollectionError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("RequiredErrorReason", FALSE)) {
/**
 * The reasons for the target error.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class RequiredErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "RequiredError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("ServerErrorReason", FALSE)) {
/**
 * Describes reasons for server errors
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class ServerErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "ServerError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("StatementErrorReason", FALSE)) {
/**
 * A bind variable has not been bound to a value.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class StatementErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "StatementError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("StringLengthErrorReason", FALSE)) {
/**
 * The value returned if the actual value is not exposed by the requested API version.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class StringLengthErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "StringLengthError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("TargetPlatform", FALSE)) {
/**
 * Indicates the target platform.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class TargetPlatform {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "TargetPlatform";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("TeamErrorReason", FALSE)) {
/**
 * The reasons for the target error.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class TeamErrorReason {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "TeamError.Reason";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("TimeUnit", FALSE)) {
/**
 * Represent the possible time units for frequency capping.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class TimeUnit {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "TimeUnit";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct() {
  }

}}

if (!class_exists("CreateAdUnit", FALSE)) {
/**
 * Creates a new {@link AdUnit}.
 * 
 * The following fields are required:
 * <ul>
 * <li>{@link AdUnit#name}</li>
 * <li>{@link AdUnit#parentId}</li>
 * </ul>
 * 
 * @param adUnit the ad unit to create
 * @return the new ad unit with its ID set
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class CreateAdUnit {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "";

  /**
   * @access public
   * @var AdUnit
   */
  public $adUnit;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($adUnit = null) {
    $this->adUnit = $adUnit;
  }

}}

if (!class_exists("CreateAdUnitResponse", FALSE)) {
/**
 * 
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class CreateAdUnitResponse {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "";

  /**
   * @access public
   * @var AdUnit
   */
  public $rval;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($rval = null) {
    $this->rval = $rval;
  }

}}

if (!class_exists("CreateAdUnits", FALSE)) {
/**
 * Creates new {@link AdUnit} objects.
 * 
 * @param adUnits the ad units to create
 * @return the created ad units, with their IDs filled in
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class CreateAdUnits {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "";

  /**
   * @access public
   * @var AdUnit[]
   */
  public $adUnits;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($adUnits = null) {
    $this->adUnits = $adUnits;
  }

}}

if (!class_exists("CreateAdUnitsResponse", FALSE)) {
/**
 * 
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class CreateAdUnitsResponse {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "";

  /**
   * @access public
   * @var AdUnit[]
   */
  public $rval;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($rval = null) {
    $this->rval = $rval;
  }

}}

if (!class_exists("GetAdUnit", FALSE)) {
/**
 * Returns the {@link AdUnit} uniquely identified by the given ID.
 * 
 * @param adUnitId ID of the ad unit, which must already exist
 * @return the {@code AdUnit} uniquely identified by the given ID
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class GetAdUnit {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "";

  /**
   * @access public
   * @var string
   */
  public $adUnitId;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($adUnitId = null) {
    $this->adUnitId = $adUnitId;
  }

}}

if (!class_exists("GetAdUnitResponse", FALSE)) {
/**
 * 
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class GetAdUnitResponse {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "";

  /**
   * @access public
   * @var AdUnit
   */
  public $rval;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($rval = null) {
    $this->rval = $rval;
  }

}}

if (!class_exists("GetAdUnitSizesByStatement", FALSE)) {
/**
 * Gets a set of {@link AdUnitSize} objects that satisfy the given
 * {@link Statement#query}. The following fields are supported for filtering:
 * 
 * <table>
 * <tr>
 * <th scope="col">PQL Property</th> <th scope="col">Object Property</th>
 * </tr>
 * <tr>
 * <td>{@code targetPlatform}</td>
 * <td>{@link TargetPlatform}</td>
 * </tr>
 * </table>
 * An exception will be thrown for queries with unsupported fields.
 * 
 * Paging is not supported, as aren't the LIMIT and OFFSET PQL keywords.
 * 
 * Only "=" operator is supported.
 * 
 * @param filterStatement a Publisher Query Language statement used to filter
 * a set of ad unit sizes
 * @return the ad unit sizes that match the given filter
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class GetAdUnitSizesByStatement {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "";

  /**
   * @access public
   * @var Statement
   */
  public $filterStatement;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($filterStatement = null) {
    $this->filterStatement = $filterStatement;
  }

}}

if (!class_exists("GetAdUnitSizesByStatementResponse", FALSE)) {
/**
 * 
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class GetAdUnitSizesByStatementResponse {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "";

  /**
   * @access public
   * @var AdUnitSize[]
   */
  public $rval;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($rval = null) {
    $this->rval = $rval;
  }

}}

if (!class_exists("GetAdUnitsByStatement", FALSE)) {
/**
 * Gets a {@link AdUnitPage} of {@link AdUnit} objects that satisfy the given
 * {@link Statement#query}. The following fields are supported for filtering:
 * 
 * <table>
 * <tr>
 * <th scope="col">PQL Property</th> <th scope="col">Object Property</th>
 * </tr>
 * <tr>
 * <td>{@code adUnitCode}</td>
 * <td>{@link AdUnit#adUnitCode}</td>
 * </tr>
 * <tr>
 * <td>{@code id}</td>
 * <td>{@link AdUnit#id}</td>
 * </tr>
 * <tr>
 * <td>{@code name}</td>
 * <td>{@link AdUnit#name}</td>
 * </tr>
 * <tr>
 * <td>{@code parentId}</td>
 * <td>{@link AdUnit#parentId}</td>
 * </tr>
 * <tr>
 * <td>{@code status}</td>
 * <td>{@link AdUnit#status}</td>
 * </tr>
 * <tr>
 * <td>{@code lastModifiedDateTime}</td>
 * <td>{@link AdUnit#lastModifiedDateTime}</td>
 * </tr>
 * </table>
 * 
 * @param filterStatement a Publisher Query Language statement used to filter
 * a set of ad units
 * @return the ad units that match the given filter
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class GetAdUnitsByStatement {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "";

  /**
   * @access public
   * @var Statement
   */
  public $filterStatement;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($filterStatement = null) {
    $this->filterStatement = $filterStatement;
  }

}}

if (!class_exists("GetAdUnitsByStatementResponse", FALSE)) {
/**
 * 
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class GetAdUnitsByStatementResponse {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "";

  /**
   * @access public
   * @var AdUnitPage
   */
  public $rval;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($rval = null) {
    $this->rval = $rval;
  }

}}

if (!class_exists("PerformAdUnitAction", FALSE)) {
/**
 * Performs actions on {@link AdUnit} objects that match the given
 * {@link Statement#query}.
 * 
 * @param adUnitAction the action to perform
 * @param filterStatement a Publisher Query Language statement used to filter
 * a set of ad units
 * @return the result of the action performed
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class PerformAdUnitAction {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "";

  /**
   * @access public
   * @var AdUnitAction
   */
  public $adUnitAction;

  /**
   * @access public
   * @var Statement
   */
  public $filterStatement;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($adUnitAction = null, $filterStatement = null) {
    $this->adUnitAction = $adUnitAction;
    $this->filterStatement = $filterStatement;
  }

}}

if (!class_exists("PerformAdUnitActionResponse", FALSE)) {
/**
 * 
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class PerformAdUnitActionResponse {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "";

  /**
   * @access public
   * @var UpdateResult
   */
  public $rval;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($rval = null) {
    $this->rval = $rval;
  }

}}

if (!class_exists("UpdateAdUnit", FALSE)) {
/**
 * Updates the specified {@link AdUnit}.
 * 
 * @param adUnit the ad unit to update
 * @return the updated ad unit
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class UpdateAdUnit {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "";

  /**
   * @access public
   * @var AdUnit
   */
  public $adUnit;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($adUnit = null) {
    $this->adUnit = $adUnit;
  }

}}

if (!class_exists("UpdateAdUnitResponse", FALSE)) {
/**
 * 
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class UpdateAdUnitResponse {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "";

  /**
   * @access public
   * @var AdUnit
   */
  public $rval;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($rval = null) {
    $this->rval = $rval;
  }

}}

if (!class_exists("UpdateAdUnits", FALSE)) {
/**
 * Updates the specified {@link AdUnit} objects.
 * 
 * @param adUnits the ad units to update
 * @return the updated ad units
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class UpdateAdUnits {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "";

  /**
   * @access public
   * @var AdUnit[]
   */
  public $adUnits;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($adUnits = null) {
    $this->adUnits = $adUnits;
  }

}}

if (!class_exists("UpdateAdUnitsResponse", FALSE)) {
/**
 * 
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class UpdateAdUnitsResponse {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "";

  /**
   * @access public
   * @var AdUnit[]
   */
  public $rval;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($rval = null) {
    $this->rval = $rval;
  }

}}

if (!class_exists("ActivateAdUnits", FALSE)) {
/**
 * The action used for activating {@link AdUnit} objects.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class ActivateAdUnits extends AdUnitAction {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "ActivateAdUnits";

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($AdUnitActionType = null) {
    parent::__construct();
    $this->AdUnitActionType = $AdUnitActionType;
  }

}}

if (!class_exists("AdSenseAccountError", FALSE)) {
/**
 * Error for AdSense related API calls.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdSenseAccountError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdSenseAccountError";

  /**
   * @access public
   * @var tnsAdSenseAccountErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("AdUnitAfcSizeError", FALSE)) {
/**
 * Caused by supplying sizes that are not compatible with the Afc sizes.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdUnitAfcSizeError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdUnitAfcSizeError";

  /**
   * @access public
   * @var tnsAdUnitAfcSizeErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("AdUnitCodeError", FALSE)) {
/**
 * Lists the generic errors associated with {@link AdUnit#adUnitCode}.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdUnitCodeError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdUnitCodeError";

  /**
   * @access public
   * @var tnsAdUnitCodeErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("AdUnitHierarchyError", FALSE)) {
/**
 * Caused by creating an {@link AdUnit} object with an invalid hierarchy.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class AdUnitHierarchyError extends ApiError {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "AdUnitHierarchyError";

  /**
   * @access public
   * @var tnsAdUnitHierarchyErrorReason
   */
  public $reason;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($reason = null, $fieldPath = null, $trigger = null, $errorString = null, $ApiErrorType = null) {
    parent::__construct();
    $this->reason = $reason;
    $this->fieldPath = $fieldPath;
    $this->trigger = $trigger;
    $this->errorString = $errorString;
    $this->ApiErrorType = $ApiErrorType;
  }

}}

if (!class_exists("ApiException", FALSE)) {
/**
 * Exception class for holding a list of service errors.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class ApiException extends ApplicationException {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "ApiException";

  /**
   * @access public
   * @var ApiError[]
   */
  public $errors;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($errors = null, $message = null, $ApplicationExceptionType = null) {
    parent::__construct();
    $this->errors = $errors;
    $this->message = $message;
    $this->ApplicationExceptionType = $ApplicationExceptionType;
  }

}}

if (!class_exists("BooleanValue", FALSE)) {
/**
 * Contains a boolean value.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class BooleanValue extends Value {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "BooleanValue";

  /**
   * @access public
   * @var boolean
   */
  public $value;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($value = null, $ValueType = null) {
    parent::__construct();
    $this->value = $value;
    $this->ValueType = $ValueType;
  }

}}

if (!class_exists("DateTimeValue", FALSE)) {
/**
 * Contains a date-time value.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class DateTimeValue extends Value {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "DateTimeValue";

  /**
   * @access public
   * @var DateTime
   */
  public $value;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($value = null, $ValueType = null) {
    parent::__construct();
    $this->value = $value;
    $this->ValueType = $ValueType;
  }

}}

if (!class_exists("NumberValue", FALSE)) {
/**
 * Contains a numeric value.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class NumberValue extends Value {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "NumberValue";

  /**
   * @access public
   * @var string
   */
  public $value;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($value = null, $ValueType = null) {
    parent::__construct();
    $this->value = $value;
    $this->ValueType = $ValueType;
  }

}}

if (!class_exists("TextValue", FALSE)) {
/**
 * Contains a string value.
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class TextValue extends Value {

  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const XSI_TYPE = "TextValue";

  /**
   * @access public
   * @var string
   */
  public $value;

  /**
   * Gets the namesapce of this class
   * @return the namespace of this class
   */
  public function getNamespace() {
    return self::WSDL_NAMESPACE;
  }

  /**
   * Gets the xsi:type name of this class
   * @return the xsi:type name of this class
   */
  public function getXsiTypeName() {
    return self::XSI_TYPE;
  }

  public function __construct($value = null, $ValueType = null) {
    parent::__construct();
    $this->value = $value;
    $this->ValueType = $ValueType;
  }

}}

if (!class_exists("InventoryService", FALSE)) {
/**
 * InventoryService
 * @package GoogleApiAdsDfp
 * @subpackage v201208
 */
class InventoryService extends DfpSoapClient {

  const SERVICE_NAME = "InventoryService";
  const WSDL_NAMESPACE = "https://www.google.com/apis/ads/publisher/v201208";
  const ENDPOINT = "https://www.google.com/apis/ads/publisher/v201208/InventoryService";

  /**
   * The endpoint of the service
   * @var string
   */
  public static $endpoint = "https://www.google.com/apis/ads/publisher/v201208/InventoryService";
  /**
   * Default class map for wsdl=>php
   * @access private
   * @var array
   */
  public static $classmap = array(
    "ActivateAdUnits" => "ActivateAdUnits",
    "AdSenseAccountError" => "AdSenseAccountError",
    "AdSenseSettings" => "AdSenseSettings",
    "AdSenseSettingsInheritedProperty" => "AdSenseSettingsInheritedProperty",
    "AdUnitAction" => "AdUnitAction",
    "AdUnitAfcSizeError" => "AdUnitAfcSizeError",
    "AdUnitCodeError" => "AdUnitCodeError",
    "AdUnit" => "AdUnit",
    "AdUnitHierarchyError" => "AdUnitHierarchyError",
    "AdUnitPage" => "AdUnitPage",
    "AdUnitParent" => "AdUnitParent",
    "ApiError" => "ApiError",
    "ApiException" => "ApiException",
    "ApiVersionError" => "ApiVersionError",
    "ApplicationException" => "ApplicationException",
    "ArchiveAdUnits" => "ArchiveAdUnits",
    "AssignAdUnitsToPlacement" => "AssignAdUnitsToPlacement",
    "Authentication" => "Authentication",
    "AuthenticationError" => "AuthenticationError",
    "BooleanValue" => "BooleanValue",
    "ClientLogin" => "ClientLogin",
    "CommonError" => "CommonError",
    "CreativeWrapperError" => "CreativeWrapperError",
    "Date" => "Date",
    "DateTime" => "DfpDateTime",
    "DateTimeValue" => "DateTimeValue",
    "DeactivateAdUnits" => "DeactivateAdUnits",
    "EntityLimitReachedError" => "EntityLimitReachedError",
    "FeatureError" => "FeatureError",
    "FrequencyCap" => "FrequencyCap",
    "FrequencyCapError" => "FrequencyCapError",
    "InternalApiError" => "InternalApiError",
    "InvalidColorError" => "InvalidColorError",
    "InventoryUnitError" => "InventoryUnitError",
    "InventoryUnitPartnerAssociationError" => "InventoryUnitPartnerAssociationError",
    "AdUnitSize" => "AdUnitSize",
    "InventoryUnitSizesError" => "InventoryUnitSizesError",
    "AdUnitTypeError" => "AdUnitTypeError",
    "LabelFrequencyCap" => "LabelFrequencyCap",
    "NotNullError" => "NotNullError",
    "NullError" => "NullError",
    "NumberValue" => "NumberValue",
    "OAuth" => "DfpOAuth",
    "ParseError" => "ParseError",
    "PermissionError" => "PermissionError",
    "PublisherQueryLanguageContextError" => "PublisherQueryLanguageContextError",
    "PublisherQueryLanguageSyntaxError" => "PublisherQueryLanguageSyntaxError",
    "QuotaError" => "QuotaError",
    "RegExError" => "RegExError",
    "RequiredCollectionError" => "RequiredCollectionError",
    "RequiredError" => "RequiredError",
    "ServerError" => "ServerError",
    "Size" => "Size",
    "Size_StringMapEntry" => "Size_StringMapEntry",
    "SoapRequestHeader" => "SoapRequestHeader",
    "SoapResponseHeader" => "SoapResponseHeader",
    "Statement" => "Statement",
    "StatementError" => "StatementError",
    "StringLengthError" => "StringLengthError",
    "String_ValueMapEntry" => "String_ValueMapEntry",
    "TeamError" => "TeamError",
    "TextValue" => "TextValue",
    "TypeError" => "TypeError",
    "UniqueError" => "UniqueError",
    "UpdateResult" => "UpdateResult",
    "Value" => "Value",
    "AdSenseAccountError.Reason" => "AdSenseAccountErrorReason",
    "AdSenseSettings.AdType" => "AdSenseSettingsAdType",
    "AdSenseSettings.BorderStyle" => "AdSenseSettingsBorderStyle",
    "AdSenseSettings.FontFamily" => "AdSenseSettingsFontFamily",
    "AdSenseSettings.FontSize" => "AdSenseSettingsFontSize",
    "AdUnitAfcSizeError.Reason" => "AdUnitAfcSizeErrorReason",
    "AdUnitCodeError.Reason" => "AdUnitCodeErrorReason",
    "AdUnit.TargetWindow" => "AdUnitTargetWindow",
    "AdUnitHierarchyError.Reason" => "AdUnitHierarchyErrorReason",
    "ApiVersionError.Reason" => "ApiVersionErrorReason",
    "AuthenticationError.Reason" => "AuthenticationErrorReason",
    "CommonError.Reason" => "CommonErrorReason",
    "CreativeWrapperError.Reason" => "CreativeWrapperErrorReason",
    "EnvironmentType" => "EnvironmentType",
    "FeatureError.Reason" => "FeatureErrorReason",
    "FrequencyCapError.Reason" => "FrequencyCapErrorReason",
    "InternalApiError.Reason" => "InternalApiErrorReason",
    "InvalidColorError.Reason" => "InvalidColorErrorReason",
    "InventoryStatus" => "InventoryStatus",
    "InventoryUnitError.Reason" => "InventoryUnitErrorReason",
    "InventoryUnitPartnerAssociationError.Reason" => "InventoryUnitPartnerAssociationErrorReason",
    "InventoryUnitSizesError.Reason" => "InventoryUnitSizesErrorReason",
    "AdUnitTypeError.Reason" => "AdUnitTypeErrorReason",
    "MobilePlatform" => "MobilePlatform",
    "NotNullError.Reason" => "NotNullErrorReason",
    "NullError.Reason" => "NullErrorReason",
    "ParseError.Reason" => "ParseErrorReason",
    "PermissionError.Reason" => "PermissionErrorReason",
    "PublisherQueryLanguageContextError.Reason" => "PublisherQueryLanguageContextErrorReason",
    "PublisherQueryLanguageSyntaxError.Reason" => "PublisherQueryLanguageSyntaxErrorReason",
    "QuotaError.Reason" => "QuotaErrorReason",
    "RegExError.Reason" => "RegExErrorReason",
    "RequiredCollectionError.Reason" => "RequiredCollectionErrorReason",
    "RequiredError.Reason" => "RequiredErrorReason",
    "ServerError.Reason" => "ServerErrorReason",
    "StatementError.Reason" => "StatementErrorReason",
    "StringLengthError.Reason" => "StringLengthErrorReason",
    "TargetPlatform" => "TargetPlatform",
    "TeamError.Reason" => "TeamErrorReason",
    "TimeUnit" => "TimeUnit",
    "createAdUnit" => "CreateAdUnit",
    "createAdUnitResponse" => "CreateAdUnitResponse",
    "createAdUnits" => "CreateAdUnits",
    "createAdUnitsResponse" => "CreateAdUnitsResponse",
    "getAdUnit" => "GetAdUnit",
    "getAdUnitResponse" => "GetAdUnitResponse",
    "getAdUnitSizesByStatement" => "GetAdUnitSizesByStatement",
    "getAdUnitSizesByStatementResponse" => "GetAdUnitSizesByStatementResponse",
    "getAdUnitsByStatement" => "GetAdUnitsByStatement",
    "getAdUnitsByStatementResponse" => "GetAdUnitsByStatementResponse",
    "performAdUnitAction" => "PerformAdUnitAction",
    "performAdUnitActionResponse" => "PerformAdUnitActionResponse",
    "updateAdUnit" => "UpdateAdUnit",
    "updateAdUnitResponse" => "UpdateAdUnitResponse",
    "updateAdUnits" => "UpdateAdUnits",
    "updateAdUnitsResponse" => "UpdateAdUnitsResponse",
  );


  /**
   * Constructor using wsdl location and options array
   * @param string $wsdl WSDL location for this service
   * @param array $options Options for the SoapClient
   */
  public function __construct($wsdl=null, $options, $user) {
    $options["classmap"] = self::$classmap;
    parent::__construct($wsdl, $options, $user, self::SERVICE_NAME,
        self::WSDL_NAMESPACE);
  }
  /**
   * Creates a new {@link AdUnit}.
   * 
   * The following fields are required:
   * <ul>
   * <li>{@link AdUnit#name}</li>
   * <li>{@link AdUnit#parentId}</li>
   * </ul>
   * 
   * @param adUnit the ad unit to create
   * @return the new ad unit with its ID set
   */
  public function createAdUnit($adUnit) {
    $args = new CreateAdUnit($adUnit);
    $result = $this->__soapCall("createAdUnit", array($args));
    return $result->rval;
  }
  /**
   * Creates new {@link AdUnit} objects.
   * 
   * @param adUnits the ad units to create
   * @return the created ad units, with their IDs filled in
   */
  public function createAdUnits($adUnits) {
    $args = new CreateAdUnits($adUnits);
    $result = $this->__soapCall("createAdUnits", array($args));
    return $result->rval;
  }
  /**
   * Returns the {@link AdUnit} uniquely identified by the given ID.
   * 
   * @param adUnitId ID of the ad unit, which must already exist
   * @return the {@code AdUnit} uniquely identified by the given ID
   */
  public function getAdUnit($adUnitId) {
    $args = new GetAdUnit($adUnitId);
    $result = $this->__soapCall("getAdUnit", array($args));
    return $result->rval;
  }
  /**
   * Gets a set of {@link AdUnitSize} objects that satisfy the given
   * {@link Statement#query}. The following fields are supported for filtering:
   * 
   * <table>
   * <tr>
   * <th scope="col">PQL Property</th> <th scope="col">Object Property</th>
   * </tr>
   * <tr>
   * <td>{@code targetPlatform}</td>
   * <td>{@link TargetPlatform}</td>
   * </tr>
   * </table>
   * An exception will be thrown for queries with unsupported fields.
   * 
   * Paging is not supported, as aren't the LIMIT and OFFSET PQL keywords.
   * 
   * Only "=" operator is supported.
   * 
   * @param filterStatement a Publisher Query Language statement used to filter
   * a set of ad unit sizes
   * @return the ad unit sizes that match the given filter
   */
  public function getAdUnitSizesByStatement($filterStatement) {
    $args = new GetAdUnitSizesByStatement($filterStatement);
    $result = $this->__soapCall("getAdUnitSizesByStatement", array($args));
    return $result->rval;
  }
  /**
   * Gets a {@link AdUnitPage} of {@link AdUnit} objects that satisfy the given
   * {@link Statement#query}. The following fields are supported for filtering:
   * 
   * <table>
   * <tr>
   * <th scope="col">PQL Property</th> <th scope="col">Object Property</th>
   * </tr>
   * <tr>
   * <td>{@code adUnitCode}</td>
   * <td>{@link AdUnit#adUnitCode}</td>
   * </tr>
   * <tr>
   * <td>{@code id}</td>
   * <td>{@link AdUnit#id}</td>
   * </tr>
   * <tr>
   * <td>{@code name}</td>
   * <td>{@link AdUnit#name}</td>
   * </tr>
   * <tr>
   * <td>{@code parentId}</td>
   * <td>{@link AdUnit#parentId}</td>
   * </tr>
   * <tr>
   * <td>{@code status}</td>
   * <td>{@link AdUnit#status}</td>
   * </tr>
   * <tr>
   * <td>{@code lastModifiedDateTime}</td>
   * <td>{@link AdUnit#lastModifiedDateTime}</td>
   * </tr>
   * </table>
   * 
   * @param filterStatement a Publisher Query Language statement used to filter
   * a set of ad units
   * @return the ad units that match the given filter
   */
  public function getAdUnitsByStatement($filterStatement) {
    $args = new GetAdUnitsByStatement($filterStatement);
    $result = $this->__soapCall("getAdUnitsByStatement", array($args));
    return $result->rval;
  }
  /**
   * Performs actions on {@link AdUnit} objects that match the given
   * {@link Statement#query}.
   * 
   * @param adUnitAction the action to perform
   * @param filterStatement a Publisher Query Language statement used to filter
   * a set of ad units
   * @return the result of the action performed
   */
  public function performAdUnitAction($adUnitAction, $filterStatement) {
    $args = new PerformAdUnitAction($adUnitAction, $filterStatement);
    $result = $this->__soapCall("performAdUnitAction", array($args));
    return $result->rval;
  }
  /**
   * Updates the specified {@link AdUnit}.
   * 
   * @param adUnit the ad unit to update
   * @return the updated ad unit
   */
  public function updateAdUnit($adUnit) {
    $args = new UpdateAdUnit($adUnit);
    $result = $this->__soapCall("updateAdUnit", array($args));
    return $result->rval;
  }
  /**
   * Updates the specified {@link AdUnit} objects.
   * 
   * @param adUnits the ad units to update
   * @return the updated ad units
   */
  public function updateAdUnits($adUnits) {
    $args = new UpdateAdUnits($adUnits);
    $result = $this->__soapCall("updateAdUnits", array($args));
    return $result->rval;
  }
}}