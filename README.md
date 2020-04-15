# dAgent
**HTTP User Agent Detector**

`dAgent` is a simple PHP class to detect HTTP User Agents by `Device Type`, `Brand`, `Browser`, `Operating System` and `CPU Architecture`.

## Usage
`dAgent` is a standard php class which detects information from HTTP User Agent:
```php
require_once './class/dAgent.php'; // Import dAgent class file where ever it is
$dAgent = new dAgent(); // Construct to a new variable
var_dump($dAgent->data); // Show the results
```

## Properties

### data
returns an php object with the following properties:
```php
$dAgent = new dAgent();
$dAgent->data->type; // Returns string of device type 'Phone', 'Tablet', 'Desktop' or utility types such as 'Bot', 'TV', 'Console', etc.
$dAgent->data->brand; // Returns phone or tablet brand if available, otherwise 'null'
$dAgent->data->browser; // Returns the browser name if available, otherwise 'null'
$dAgent->data->os; // Returns the operating system name if available, otherwise 'null'
$dAgent->data->arch; // Returns the cpu architecture if available, otherwise 'null'
```

### isPhone
returns `true` if the user agent is a kind of `Smart Phone`, otherwise returns `false`.
```php
$dAgent = new dAgent();
if ($dAgent->isPhone) echo 'This device is a smart phone';
```

### isTablet
returns `true` if the user agent is a kind of `Tablet`, otherwise returns `false`.
```php
$dAgent = new dAgent();
if ($dAgent->isTablet) echo 'This device is a tablet';
```

### isDesktop
returns `true` if the user agent is a kind of `Standard Computer`, otherwise returns `false`.
```php
$dAgent = new dAgent();
if ($dAgent->isDesktop) echo 'This device is a desktop computer';
```

### isUtility
returns `true` if the user agent is a kind of utility including `Search Engine Bots`, `Smart TVs`, `Game Consoles` and `Smart Watches`, otherwise returns `false`.
```php
$dAgent = new dAgent();
if ($dAgent->isUtility) echo 'This device is an online utility';
```
