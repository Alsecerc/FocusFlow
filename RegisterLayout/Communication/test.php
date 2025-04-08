<?php
// Include the utils file to access all functions
include 'utils.php';

// Set error reporting for better debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to safely display values for debugging
function debug_var($var) {
    if (is_array($var) || is_object($var)) {
        return '<pre>' . htmlspecialchars(print_r($var, true)) . '</pre>';
    } else {
        return htmlspecialchars(var_export($var, true));
    }
}

// Get list of all functions in utils.php
$all_functions = get_defined_functions()['user'];

// First filter the functions - fix the circular reference issue
$utils_functions = array_filter($all_functions, function($func) {
    try {
        $reflection = new ReflectionFunction($func);
        $file = $reflection->getFileName();
        return $file && (basename($file) === 'utils.php');
    } catch (Exception $e) {
        return false;
    }
});
sort($utils_functions);

// Now display debug info about the found functions
echo "<div style='background:#ffe; padding:10px; margin-bottom:20px; border:1px solid #ccc;'>";
echo "Found " . count($utils_functions) . " functions in utils.php<br>";
if (empty($utils_functions)) {
    echo "All user functions:<br>";
    echo "<pre>";
    print_r(get_defined_functions()['user']);
    echo "</pre>";
}
echo "</div>";

// Get function name from URL if present
$function_name = isset($_GET['function']) ? $_GET['function'] : '';

$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['function'])) {
    $function_name = $_POST['function'];
    
    // Check if the function actually exists before trying to call it
    if (!function_exists($function_name)) {
        $error = "Function '$function_name' does not exist or is not accessible.";
    } else {
        try {
            // Get function parameters
            $reflection = new ReflectionFunction($function_name);
            $params = $reflection->getParameters();
            $param_values = [];
            $missing_params = [];
            
            // Collect parameters from POST
            foreach ($params as $param) {
                $param_name = $param->getName();
                if (isset($_POST[$param_name])) {
                    // Handle special cases for parameters
                    if (in_array($param_name, ['groupmembers', 'GroupEmail']) && !empty($_POST[$param_name])) {
                        $param_values[] = explode(',', $_POST[$param_name]);
                    } else {
                        $param_values[] = $_POST[$param_name];
                    }
                } elseif ($param->isOptional()) {
                    $param_values[] = $param->getDefaultValue();
                } else {
                    $missing_params[] = $param_name;
                }
            }
            
            if (!empty($missing_params)) {
                $error = "Missing required parameters: " . implode(', ', $missing_params);
            } else {
                // Execute the function with the collected parameters
                ob_start();
                $function_result = call_user_func_array($function_name, $param_values);
                $output = ob_get_clean();
                
                // Combine function return value with any output
                $result = $output;
                if ($function_result !== null && $function_result !== "") {
                    if ($result !== "") {
                        $result .= "\n\n";
                    }
                    $result .= "Return value: " . debug_var($function_result);
                }
            }
        } catch (Exception $e) {
            $error = "Error executing function: " . $e->getMessage();
        } catch (Error $e) {
            $error = "PHP Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>utils.php Test Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .function-selector {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        .function-btn {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .function-btn:hover {
            background-color: #e0e0e0;
        }
        .function-btn.active {
            background-color: #007bff;
            color: white;
        }
        .param-form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .submit-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        .result {
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
            overflow-x: auto;
        }
        .error {
            background-color: #fee;
            border-left: 4px solid #dc3545;
        }
        pre {
            margin: 0;
            white-space: pre-wrap;
        }
        .function-description {
            margin-top: 10px;
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>utils.php Function Test Page</h1>
        
        <h2>Select a Function to Test:</h2>
        <form method="get" action="">
            <select name="function" id="function-select" onchange="this.form.submit()" style="padding:8px; width:100%; max-width:400px;">
                <option value="">-- Select a function --</option>
                <?php 
                if (!empty($utils_functions)):
                    foreach ($utils_functions as $func): 
                ?>
                    <option value="<?php echo htmlspecialchars($func); ?>" <?php echo ($function_name === $func) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($func); ?>
                    </option>
                <?php 
                    endforeach;
                else:
                ?>
                    <option value="" disabled>No functions found in utils.php</option>
                <?php endif; ?>
            </select>
        </form>
        
        <?php if ($function_name): ?>
            <?php if (!function_exists($function_name)): ?>
                <div class="result error">
                    <p>Function "<?php echo htmlspecialchars($function_name); ?>" does not exist or is not accessible.</p>
                </div>
            <?php else: ?>
                <h2>Test Function: <?php echo htmlspecialchars($function_name); ?></h2>
                
                <div class="function-description">
                    <?php
                    try {
                        $reflection = new ReflectionFunction($function_name);
                        $params = $reflection->getParameters();
                        echo "Parameters: ";
                        if (empty($params)) {
                            echo "None";
                        } else {
                            echo implode(', ', array_map(function($param) {
                                $str = '$' . $param->getName();
                                if ($param->isOptional()) {
                                    $str .= ' (optional)';
                                    try {
                                        $default = $param->getDefaultValue();
                                        $str .= ' = ' . var_export($default, true);
                                    } catch (Exception $e) {
                                        // Skip showing default if it can't be exported
                                    }
                                }
                                return $str;
                            }, $params));
                        }
                    } catch (Exception $e) {
                        echo "Error getting function parameters: " . $e->getMessage();
                    }
                    ?>
                </div>
                
                <form method="post" class="param-form">
                    <input type="hidden" name="function" value="<?php echo htmlspecialchars($function_name); ?>">
                    
                    <?php
                    // Generate form fields based on function parameters
                    try {
                        $reflection = new ReflectionFunction($function_name);
                        $params = $reflection->getParameters();
                        
                        foreach ($params as $param):
                            $param_name = $param->getName();
                    ?>
                    <div class="form-group">
                        <label for="<?php echo $param_name; ?>"><?php echo $param_name; ?>:
                            <?php if ($param->isOptional()): ?>
                                <small>(optional)</small>
                            <?php endif; ?>
                        </label>
                        <?php if (in_array($param_name, ['groupmembers', 'GroupEmail'])): ?>
                            <textarea name="<?php echo $param_name; ?>" id="<?php echo $param_name; ?>" rows="3" placeholder="Comma separated values (e.g., email1@example.com, email2@example.com)"></textarea>
                        <?php elseif ($param_name === 'Status'): ?>
                            <select name="<?php echo $param_name; ?>" id="<?php echo $param_name; ?>">
                                <option value="Active">Active</option>
                                <option value="Muted">Muted</option>
                            </select>
                        <?php elseif ($param_name === 'MessageType'): ?>
                            <select name="<?php echo $param_name; ?>" id="<?php echo $param_name; ?>">
                                <option value="TEXT">TEXT</option>
                                <option value="IMAGE">IMAGE</option>
                                <option value="FILE">FILE</option>
                            </select>
                        <?php elseif ($param_name === 'ROLE'): ?>
                            <select name="<?php echo $param_name; ?>" id="<?php echo $param_name; ?>">
                                <option value="ADMIN">ADMIN</option>
                                <option value="MEMBER">MEMBER</option>
                            </select>
                        <?php elseif ($param_name === 'messageData'): ?>
                            <textarea name="<?php echo $param_name; ?>" id="<?php echo $param_name; ?>" rows="5" placeholder='{"GroupID": 1, "message": "Hello", "messageType": "TEXT"}'></textarea>
                        <?php else: ?>
                            <input type="text" name="<?php echo $param_name; ?>" id="<?php echo $param_name; ?>">
                        <?php endif; ?>
                    </div>
                    <?php
                        endforeach;
                    } catch (Exception $e) {
                        echo "<div class='error'>Error generating form fields: " . htmlspecialchars($e->getMessage()) . "</div>";
                    }
                    ?>
                    
                    <button type="submit" class="submit-btn">Execute Function</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php if ($result !== null || $error !== null): ?>
        <h2>Result:</h2>
        <div class="result <?php echo $error ? 'error' : ''; ?>">
            <?php if ($error): ?>
                <pre><?php echo htmlspecialchars($error); ?></pre>
            <?php else: ?>
                <?php if (is_string($result)): ?>
                    <pre><?php echo htmlspecialchars($result); ?></pre>
                <?php else: ?>
                    <pre><?php echo debug_var($result); ?></pre>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <script>
        function selectFunction(funcName) {
            window.location.href = '?function=' + encodeURIComponent(funcName);
        }
        
        // Pre-select function from URL parameter
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const funcParam = urlParams.get('function');
            if (funcParam) {
                document.querySelector('form input[name="function"]').value = funcParam;
                document.querySelectorAll('.function-btn').forEach(btn => {
                    if (btn.textContent === funcParam) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
            }
        });
    </script>
</body>
</html>