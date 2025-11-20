<?php
/**
 * Debug Panel Component
 * Floating diagnostic widget for troubleshooting
 */

// Only load if not already loaded
if (defined('DEBUG_PANEL_LOADED')) {
    return;
}
define('DEBUG_PANEL_LOADED', true);

// Get current page info
$currentPage = basename($_SERVER['PHP_SELF']);
$currentUrl = $_SERVER['REQUEST_URI'] ?? '/';

// Check user session
$isLoggedIn = isset($_SESSION[SESSION_USER_KEY]) && !empty($_SESSION[SESSION_USER_KEY]);
$userEmail = $isLoggedIn ? ($_SESSION[SESSION_USER_KEY]['email'] ?? 'Unknown') : 'Not logged in';
$userTier = $isLoggedIn ? ($_SESSION[SESSION_USER_KEY]['tier'] ?? 'unknown') : 'N/A';

// API Configuration
$apiBaseUrl = defined('API_BASE_URL') ? API_BASE_URL : 'Not configured';
$apiEndpoint = $apiBaseUrl . (defined('API_PREFIX') ? API_PREFIX : '');

// Get PHP errors if any
$lastError = error_get_last();
$hasPhpError = $lastError !== null && in_array($lastError['type'], [E_ERROR, E_WARNING, E_PARSE]);

// Session info
$sessionId = session_id();
$sessionAge = isset($_SESSION['created_at']) ? time() - $_SESSION['created_at'] : 'Unknown';

// Get last API call info from session (if exists)
$lastApiCall = $_SESSION['debug_last_api_call'] ?? null;
$lastApiError = $_SESSION['debug_last_api_error'] ?? null;

// Get registration error from current request
$currentError = $registerError ?? null;

// Get detailed registration error from session
$detailedRegError = $_SESSION['debug_last_registration_error'] ?? null;
?>

<style>
    #debug-panel {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 400px;
        max-height: 80vh;
        background: #fff;
        border: 2px solid #106147;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        transition: all 0.3s ease;
    }

    #debug-panel.minimized {
        height: auto;
        max-height: 50px;
    }

    #debug-panel-header {
        background: #106147;
        color: white;
        padding: 10px 15px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        user-select: none;
        border-radius: 6px 6px 0 0;
    }

    #debug-panel-header:hover {
        background: #0d4d39;
    }

    #debug-panel-body {
        padding: 15px;
        max-height: calc(80vh - 60px);
        overflow-y: auto;
        display: block;
    }

    #debug-panel.minimized #debug-panel-body {
        display: none;
    }

    .debug-section {
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e0e0e0;
    }

    .debug-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .debug-section-title {
        font-weight: bold;
        color: #106147;
        margin-bottom: 8px;
        font-size: 13px;
    }

    .debug-item {
        display: flex;
        justify-content: space-between;
        margin: 4px 0;
        line-height: 1.5;
    }

    .debug-label {
        color: #666;
        flex-shrink: 0;
        margin-right: 10px;
    }

    .debug-value {
        color: #333;
        word-break: break-all;
        text-align: right;
        font-weight: 500;
    }

    .status-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 5px;
    }

    .status-good {
        background-color: #28a745;
    }

    .status-warning {
        background-color: #ffc107;
    }

    .status-error {
        background-color: #dc3545;
    }

    .status-unknown {
        background-color: #6c757d;
    }

    #debug-toggle-icon {
        transition: transform 0.3s ease;
    }

    #debug-panel.minimized #debug-toggle-icon {
        transform: rotate(180deg);
    }

    .debug-error-box {
        background: #fff3cd;
        border: 1px solid #ffc107;
        padding: 8px;
        border-radius: 4px;
        margin-top: 5px;
        font-size: 11px;
        color: #856404;
    }

    .debug-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .copy-logs-btn {
        background: #106147;
        color: white;
        border: none;
        padding: 3px 8px;
        border-radius: 3px;
        cursor: pointer;
        font-size: 10px;
        transition: background 0.2s;
    }

    .copy-logs-btn:hover {
        background: #0d4d39;
    }

    .copy-logs-btn:active {
        background: #094030;
    }
</style>

<div id="debug-panel" class="minimized">
    <div id="debug-panel-header" onclick="toggleDebugPanel()">
        <div>
            <strong>🔧 Debug Panel</strong>
            <span id="api-status-mini" style="margin-left: 10px;">
                <span class="status-indicator status-unknown"></span>
                <span id="api-status-text-mini">Checking...</span>
            </span>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <button class="copy-logs-btn" onclick="copyAllDebugInfo(event)" title="Copy all debug information">📋 Copy
                All</button>
            <span id="debug-toggle-icon">▼</span>
        </div>
    </div>
    <div id="debug-panel-body">

        <!-- API Status Section -->
        <div class="debug-section">
            <div class="debug-section-title">🌐 API Status</div>
            <div class="debug-item">
                <span class="debug-label">Backend:</span>
                <span class="debug-value" id="api-health-status">
                    <span class="status-indicator status-unknown"></span> Checking...
                </span>
            </div>
            <div class="debug-item">
                <span class="debug-label">Base URL:</span>
                <span class="debug-value" style="font-size: 10px;"><?php echo htmlspecialchars($apiBaseUrl); ?></span>
            </div>
            <div class="debug-item">
                <span class="debug-label">Response Time:</span>
                <span class="debug-value" id="api-response-time">-</span>
            </div>
            <div class="debug-item">
                <span class="debug-label">Database:</span>
                <span class="debug-value" id="api-db-status">
                    <span class="status-indicator status-unknown"></span> Unknown
                </span>
            </div>
        </div>

        <!-- User Session Section -->
        <div class="debug-section">
            <div class="debug-section-title">👤 User Session</div>
            <div class="debug-item">
                <span class="debug-label">Status:</span>
                <span class="debug-value">
                    <span class="status-indicator <?php echo $isLoggedIn ? 'status-good' : 'status-warning'; ?>"></span>
                    <?php echo $isLoggedIn ? 'Logged In' : 'Not Logged In'; ?>
                </span>
            </div>
            <?php if ($isLoggedIn): ?>
                <div class="debug-item">
                    <span class="debug-label">Email:</span>
                    <span class="debug-value"><?php echo htmlspecialchars($userEmail); ?></span>
                </div>
                <div class="debug-item">
                    <span class="debug-label">Tier:</span>
                    <span class="debug-value"><?php echo htmlspecialchars($userTier); ?></span>
                </div>
            <?php endif; ?>
            <div class="debug-item">
                <span class="debug-label">Session ID:</span>
                <span class="debug-value" style="font-size: 10px;"><?php echo substr($sessionId, 0, 12); ?>...</span>
            </div>
        </div>

        <!-- Page Info Section -->
        <div class="debug-section">
            <div class="debug-section-title">📄 Page Info</div>
            <div class="debug-item">
                <span class="debug-label">Current Page:</span>
                <span class="debug-value"><?php echo htmlspecialchars($currentPage); ?></span>
            </div>
            <div class="debug-item">
                <span class="debug-label">URL Path:</span>
                <span class="debug-value" style="font-size: 10px;"><?php echo htmlspecialchars($currentUrl); ?></span>
            </div>
            <div class="debug-item">
                <span class="debug-label">Method:</span>
                <span class="debug-value"><?php echo htmlspecialchars($_SERVER['REQUEST_METHOD']); ?></span>
            </div>
        </div>

        <!-- Current Error Section -->
        <?php if ($currentError): ?>
            <div class="debug-section">
                <div class="debug-section-title">❌ Registration Error</div>
                <div class="debug-error-box">
                    <?php echo $currentError; ?>
                </div>
                <?php if ($detailedRegError): ?>
                    <div class="debug-error-box" style="margin-top: 5px; background: #ffe5e5;">
                        <strong>Details:</strong><br>
                        <?php if (isset($detailedRegError['http_code'])): ?>
                            HTTP Code: <?php echo $detailedRegError['http_code']; ?><br>
                        <?php endif; ?>
                        <?php if (isset($detailedRegError['exception'])): ?>
                            Exception: <?php echo htmlspecialchars($detailedRegError['exception']); ?><br>
                        <?php endif; ?>
                        <?php if (isset($detailedRegError['full_response'])): ?>
                            <details style="margin-top: 5px;">
                                <summary style="cursor: pointer; font-weight: bold;">Full Response</summary>
                                <pre
                                    style="margin: 5px 0 0 0; font-size: 9px; overflow-x: auto;"><?php echo htmlspecialchars(json_encode($detailedRegError['full_response'], JSON_PRETTY_PRINT)); ?></pre>
                            </details>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Last API Call Section -->
        <div class="debug-section">
            <div class="debug-section-title">📡 Last API Call</div>
            <div id="last-api-call-info">
                <?php if ($lastApiCall): ?>
                    <div class="debug-item">
                        <span class="debug-label">Endpoint:</span>
                        <span class="debug-value"
                            style="font-size: 10px;"><?php echo htmlspecialchars($lastApiCall['endpoint'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="debug-item">
                        <span class="debug-label">Method:</span>
                        <span class="debug-value"><?php echo htmlspecialchars($lastApiCall['method'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="debug-item">
                        <span class="debug-label">Status:</span>
                        <span class="debug-value">
                            <span
                                class="status-indicator <?php echo ($lastApiCall['status'] ?? 0) == 200 ? 'status-good' : 'status-error'; ?>"></span>
                            <?php echo htmlspecialchars($lastApiCall['status'] ?? 'N/A'); ?>
                        </span>
                    </div>
                    <?php if (isset($lastApiCall['response'])): ?>
                        <div class="debug-error-box" style="margin-top: 5px; max-height: 100px; overflow-y: auto;">
                            <strong>Response:</strong><br>
                            <pre
                                style="margin: 0; font-size: 10px; white-space: pre-wrap;"><?php echo htmlspecialchars(json_encode($lastApiCall['response'], JSON_PRETTY_PRINT)); ?></pre>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="debug-item">
                        <span class="debug-value" style="color: #999;">No API calls yet</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- JavaScript API Call Log -->
        <div class="debug-section">
            <div class="debug-section-title">🔄 Live API Calls</div>
            <div id="live-api-calls" style="max-height: 150px; overflow-y: auto; font-size: 10px;">
                <div style="color: #999; padding: 5px;">Waiting for API calls...</div>
            </div>
        </div>

        <!-- PHP Errors Section -->
        <?php if ($hasPhpError): ?>
            <div class="debug-section">
                <div class="debug-section-title">⚠️ PHP Errors</div>
                <div class="debug-error-box">
                    <strong><?php echo $lastError['type']; ?>:</strong><br>
                    <?php echo htmlspecialchars($lastError['message']); ?><br>
                    <small><?php echo htmlspecialchars($lastError['file']); ?>:<?php echo $lastError['line']; ?></small>
                </div>
            </div>
        <?php endif; ?>

        <!-- Environment Section -->
        <div class="debug-section">
            <div class="debug-section-title">⚙️ Environment</div>
            <div class="debug-item">
                <span class="debug-label">PHP Version:</span>
                <span class="debug-value"><?php echo PHP_VERSION; ?></span>
            </div>
            <div class="debug-item">
                <span class="debug-label">Server:</span>
                <span class="debug-value"
                    style="font-size: 10px;"><?php echo htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'); ?></span>
            </div>
            <div class="debug-item">
                <span class="debug-label">Timestamp:</span>
                <span class="debug-value" id="debug-timestamp"><?php echo date('H:i:s'); ?></span>
            </div>
        </div>
        <!-- Cookies Section (NEW) -->
        <div class="debug-section">
            <div class="debug-section-title">🍪 Cookies</div>
            <div id="cookies-list" style="font-size: 10px; max-height: 100px; overflow-y: auto;">
                <?php
                $cookies = $_COOKIE;
                if (!empty($cookies)):
                    foreach ($cookies as $name => $value):
                        $displayValue = strlen($value) > 30 ? substr($value, 0, 30) . '...' : $value;
                        ?>
                        <div class="debug-item" style="margin: 2px 0;">
                            <span class="debug-label"><?php echo htmlspecialchars($name); ?>:</span>
                            <span class="debug-value"
                                style="font-size: 9px;"><?php echo htmlspecialchars($displayValue); ?></span>
                        </div>
                        <?php
                    endforeach;
                else:
                    ?>
                    <div style="color: #999; padding: 5px;">No cookies set</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Request Headers Section (NEW) -->
        <div class="debug-section">
            <div class="debug-section-title">📤 Request Headers</div>
            <div style="font-size: 10px; max-height: 100px; overflow-y: auto;">
                <?php
                $headers = getallheaders();
                if ($headers):
                    foreach ($headers as $name => $value):
                        if (stripos($name, 'cookie') !== false || stripos($name, 'authorization') !== false) {
                            $value = substr($value, 0, 20) . '...';
                        }
                        ?>
                        <div class="debug-item" style="margin: 2px 0;">
                            <span class="debug-label"><?php echo htmlspecialchars($name); ?>:</span>
                            <span class="debug-value"
                                style="font-size: 9px; word-break: break-all;"><?php echo htmlspecialchars($value); ?></span>
                        </div>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>

        <!-- Performance Metrics Section (NEW) -->
        <div class="debug-section">
            <div class="debug-section-title">⚡ Performance</div>
            <div class="debug-item">
                <span class="debug-label">Memory Usage:</span>
                <span class="debug-value"><?php echo round(memory_get_usage() / 1024 / 1024, 2); ?> MB</span>
            </div>
            <div class="debug-item">
                <span class="debug-label">Peak Memory:</span>
                <span class="debug-value"><?php echo round(memory_get_peak_usage() / 1024 / 1024, 2); ?> MB</span>
            </div>
            <div class="debug-item">
                <span class="debug-label">Loaded Files:</span>
                <span class="debug-value"><?php echo count(get_included_files()); ?> files</span>
            </div>
            <div class="debug-item">
                <span class="debug-label">Page Load Time:</span>
                <span class="debug-value" id="page-load-time">Calculating...</span>
            </div>
        </div>

        <!-- Session Data Section (NEW) -->
        <div class="debug-section">
            <div class="debug-section-title">💾 Session Data</div>
            <div style="font-size: 10px; max-height: 150px; overflow-y: auto;">
                <details>
                    <summary style="cursor: pointer; font-weight: bold; margin-bottom: 5px;">View Session Contents
                        (<?php echo count($_SESSION); ?> items)</summary>
                    <pre
                        style="margin: 5px 0; background: #f5f5f5; padding: 5px; border-radius: 3px; font-size: 9px; overflow-x: auto;"><?php
                        $sessionCopy = $_SESSION;
                        // Hide sensitive data
                        if (isset($sessionCopy[SESSION_TOKEN_KEY])) {
                            $sessionCopy[SESSION_TOKEN_KEY] = substr($sessionCopy[SESSION_TOKEN_KEY], 0, 20) . '...';
                        }
                        if (isset($sessionCopy['refresh_token'])) {
                            $sessionCopy['refresh_token'] = substr($sessionCopy['refresh_token'], 0, 20) . '...';
                        }
                        echo htmlspecialchars(json_encode($sessionCopy, JSON_PRETTY_PRINT));
                        ?></pre>
                </details>
            </div>
        </div>

        <!-- LocalStorage Section (NEW - populated by JavaScript) -->
        <div class="debug-section">
            <div class="debug-section-title">🗄️ LocalStorage</div>
            <div id="localstorage-list" style="font-size: 10px; max-height: 100px; overflow-y: auto;">
                <div style="color: #999; padding: 5px;">Loading...</div>
            </div>
        </div>

        <!-- Network Errors Log (NEW - populated by JavaScript) -->
        <div class="debug-section" id="network-errors-section" style="display:none;">
            <div class="debug-section-title">🚫 Network Errors</div>
            <div id="network-errors-list" style="font-size: 10px; max-height: 120px; overflow-y: auto;"></div>
        </div>

        <!-- Console Logs (NEW - populated by JavaScript) -->
        <div class="debug-section">
            <div class="debug-section-title">📝 Console Logs</div>
            <div id="console-logs-list" style="font-size: 10px; max-height: 120px; overflow-y: auto;">
                <div style="color: #999; padding: 5px;">Capturing console logs...</div>
            </div>
        </div </div>
    </div>

    <script>
        // Intercept fetch calls to log API requests
        (function () {
            const originalFetch = window.fetch;
            const apiCalls = [];

            window.fetch = function (...args) {
                const url = args[0];
                const options = args[1] || {};
                const method = options.method || 'GET';
                const startTime = Date.now();

                console.log('🔵 DEBUG: Fetch call intercepted', { url, method, options });

                return originalFetch.apply(this, args)
                    .then(async response => {
                        const endTime = Date.now();
                        const duration = endTime - startTime;
                        const clonedResponse = response.clone();

                        let responseData;
                        try {
                            responseData = await clonedResponse.json();
                        } catch (e) {
                            responseData = await clonedResponse.text();
                        }

                        const logEntry = {
                            url,
                            method,
                            status: response.status,
                            statusText: response.statusText,
                            duration,
                            timestamp: new Date().toLocaleTimeString(),
                            requestBody: options.body ? JSON.parse(options.body) : null,
                            response: responseData
                        };

                        console.log('🟢 DEBUG: Fetch response', logEntry);
                        apiCalls.push(logEntry);

                        // Update live API calls section
                        updateLiveApiCalls(logEntry);

                        return response;
                    })
                    .catch(error => {
                        const endTime = Date.now();
                        const duration = endTime - startTime;

                        const logEntry = {
                            url,
                            method,
                            status: 'ERROR',
                            statusText: error.message,
                            duration,
                            timestamp: new Date().toLocaleTimeString(),
                            error: error.toString()
                        };

                        console.error('🔴 DEBUG: Fetch error', logEntry);
                        apiCalls.push(logEntry);

                        // Update live API calls section
                        updateLiveApiCalls(logEntry);

                        throw error;
                    });
            };

            window.debugGetApiCalls = () => apiCalls;
        })();

        // Update live API calls display
        function updateLiveApiCalls(logEntry) {
            const container = document.getElementById('live-api-calls');
            if (!container) return;

            // Clear "waiting" message
            if (container.querySelector('[style*="color: #999"]')) {
                container.innerHTML = '';
            }

            const statusClass = logEntry.status === 200 ? 'status-good' :
                logEntry.status === 'ERROR' ? 'status-error' :
                    'status-warning';

            const entry = document.createElement('div');
            entry.style.cssText = 'padding: 5px; border-bottom: 1px solid #eee; margin-bottom: 5px;';
            entry.innerHTML = `
        <div style="display: flex; justify-content: space-between; margin-bottom: 3px;">
            <span><span class="status-indicator ${statusClass}"></span> ${logEntry.method}</span>
            <span style="color: #666;">${logEntry.status}</span>
        </div>
        <div style="color: #666; font-size: 9px; word-break: break-all;">${logEntry.url.replace('<?php echo $apiBaseUrl; ?>', '...')}</div>
        <div style="color: #999; font-size: 9px;">${logEntry.timestamp} (${logEntry.duration}ms)</div>
        ${logEntry.error ? `<div style="color: #dc3545; font-size: 9px; margin-top: 3px;">${logEntry.error}</div>` : ''}
        ${logEntry.response && logEntry.response.detail ? `<div style="color: #dc3545; font-size: 9px; margin-top: 3px; background: #fff3cd; padding: 3px; border-radius: 2px;">${JSON.stringify(logEntry.response.detail)}</div>` : ''}
    `;

            // Add to top
            container.insertBefore(entry, container.firstChild);

            // Keep only last 5 entries
            while (container.children.length > 5) {
                container.removeChild(container.lastChild);
            }
        }

        // Copy all debug panel information to clipboard
        function copyAllDebugInfo(event) {
            event.stopPropagation(); // Prevent panel toggle

            // Gather all debug information
            let debugInfo = `=== Super Stats Football - Complete Debug Report ===
Generated: ${new Date().toLocaleString()}
Page: ${document.title}
URL: ${window.location.href}

`;

            // API Status
            debugInfo += `🌐 API STATUS
`;
            const apiHealth = document.getElementById('api-health-status')?.textContent.trim() || 'Unknown';
            const apiResponseTime = document.getElementById('api-response-time')?.textContent.trim() || 'N/A';
            const apiDbStatus = document.getElementById('api-db-status')?.textContent.trim() || 'Unknown';
            debugInfo += `Backend: ${apiHealth}
`;
            debugInfo += `Base URL: <?php echo addslashes($apiBaseUrl); ?>
`;
            debugInfo += `Response Time: ${apiResponseTime}
`;
            debugInfo += `Database: ${apiDbStatus}

`;

            // User Session
            debugInfo += `👤 USER SESSION
`;
            debugInfo += `Status: <?php echo $isLoggedIn ? 'Logged In' : 'Not Logged In'; ?>
`;
            <?php if ($isLoggedIn): ?>
                debugInfo += `Email: <?php echo addslashes($userEmail); ?>
`;
                debugInfo += `Tier: <?php echo addslashes($userTier); ?>
`;
            <?php endif; ?>
            debugInfo += `Session ID: <?php echo substr($sessionId, 0, 12); ?>...

`;

            // Page Info
            debugInfo += `📄 PAGE INFO
`;
            debugInfo += `Current Page: <?php echo addslashes($currentPage); ?>
`;
            debugInfo += `URL Path: <?php echo addslashes($currentUrl); ?>
`;
            debugInfo += `Method: <?php echo addslashes($_SERVER['REQUEST_METHOD']); ?>

`;

            // Registration Error
            <?php if ($currentError): ?>
                debugInfo += `❌ REGISTRATION ERROR
`;
                debugInfo += `<?php echo addslashes($currentError); ?>

`;
                <?php if ($detailedRegError): ?>
                    debugInfo += `Details:
`;
                    <?php if (isset($detailedRegError['http_code'])): ?>
                        debugInfo += `HTTP Code: <?php echo $detailedRegError['http_code']; ?>
`;
                    <?php endif; ?>
                    <?php if (isset($detailedRegError['exception'])): ?>
                        debugInfo += `Exception: <?php echo addslashes($detailedRegError['exception']); ?>
`;
                    <?php endif; ?>
                    <?php if (isset($detailedRegError['full_response'])): ?>
                        debugInfo += `Full Response: <?php echo addslashes(json_encode($detailedRegError['full_response'])); ?>
`;
                    <?php endif; ?>
                    debugInfo += `
`;
                <?php endif; ?>
            <?php endif; ?>

            // Last API Call
            debugInfo += `📡 LAST API CALL
`;
            <?php if ($lastApiCall): ?>
                debugInfo += `Endpoint: <?php echo addslashes($lastApiCall['endpoint']); ?>
`;
                debugInfo += `Method: <?php echo addslashes($lastApiCall['method']); ?>
`;
                debugInfo += `Status: <?php echo $lastApiCall['status']; ?>
`;
                debugInfo += `Duration: <?php echo addslashes($lastApiCall['duration']); ?>
`;
                debugInfo += `Timestamp: <?php echo addslashes($lastApiCall['timestamp']); ?>
`;
                <?php if (isset($lastApiCall['response'])): ?>
                    debugInfo += `Response: <?php echo addslashes(json_encode($lastApiCall['response'], JSON_PRETTY_PRINT)); ?>
`;
                <?php endif; ?>
            <?php else: ?>
                debugInfo += `No API calls yet
`;
            <?php endif; ?>
            debugInfo += `
`;

            // Live API Calls (JavaScript)
            const apiCalls = window.debugGetApiCalls ? window.debugGetApiCalls() : [];
            debugInfo += `🔄 LIVE API CALLS (${apiCalls.length})
`;

            if (apiCalls.length > 0) {
                apiCalls.forEach((log, index) => {
                    debugInfo += `
--- API Call #${index + 1} ---
`;
                    debugInfo += `Time: ${log.timestamp}
`;
                    debugInfo += `Method: ${log.method}
`;
                    debugInfo += `URL: ${log.url}
`;
                    debugInfo += `Status: ${log.status} ${log.statusText || ''}
`;
                    debugInfo += `Duration: ${log.duration}ms
`;

                    if (log.requestBody) {
                        debugInfo += `Request Body: ${JSON.stringify(log.requestBody, null, 2)}
`;
                    }

                    if (log.response) {
                        debugInfo += `Response: ${typeof log.response === 'object' ? JSON.stringify(log.response, null, 2) : log.response}
`;
                    }

                    if (log.error) {
                        debugInfo += `Error: ${log.error}
`;
                    }
                });
            } else {
                debugInfo += `No live API calls recorded yet
`;
            }
            debugInfo += `
`;

            // PHP Errors
            <?php if ($hasPhpError): ?>
                debugInfo += `⚠️ PHP ERRORS
`;
                debugInfo += `Type: <?php echo $lastError['type']; ?>
`;
                debugInfo += `Message: <?php echo addslashes($lastError['message']); ?>
`;
                debugInfo += `File: <?php echo addslashes($lastError['file']); ?>:<?php echo $lastError['line']; ?>

`;
            <?php endif; ?>

            // Environment
            debugInfo += `⚙️ ENVIRONMENT
`;
            debugInfo += `PHP Version: <?php echo PHP_VERSION; ?>
`;
            debugInfo += `Server: <?php echo addslashes($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'); ?>
`;
            debugInfo += `Timestamp: ${new Date().toLocaleTimeString()}
`;

            // Copy to clipboard
            navigator.clipboard.writeText(debugInfo).then(() => {
                const btn = event.target;
                const originalText = btn.innerHTML;
                btn.innerHTML = '✅ Copied!';
                btn.style.background = '#28a745';

                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = '#106147';
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy debug info:', err);
                alert('Failed to copy. Check console for details.');
            });
        }

        // Toggle debug panel
        function toggleDebugPanel() {
            const panel = document.getElementById('debug-panel');
            panel.classList.toggle('minimized');

            // Save state to localStorage
            const isMinimized = panel.classList.contains('minimized');
            localStorage.setItem('debugPanelMinimized', isMinimized ? 'true' : 'false');
        }

        // Restore panel state from localStorage
        window.addEventListener('DOMContentLoaded', function () {
            const isMinimized = localStorage.getItem('debugPanelMinimized');
            const panel = document.getElementById('debug-panel');

            // Default to minimized unless explicitly set to false
            if (isMinimized === 'false') {
                panel.classList.remove('minimized');
            }

            // Check API health
            checkApiHealth();

            // Update timestamp every second
            setInterval(updateTimestamp, 1000);

            // Refresh API health every 30 seconds
            setInterval(checkApiHealth, 30000);
        });

        // Check API health
        function checkApiHealth() {
            const apiUrl = '<?php echo $apiBaseUrl; ?>/health';
            const startTime = performance.now();

            fetch(apiUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    const endTime = performance.now();
                    const responseTime = Math.round(endTime - startTime);

                    document.getElementById('api-response-time').textContent = responseTime + ' ms';

                    if (!response.ok) {
                        throw new Error('HTTP ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    // Update API health status
                    const healthStatus = document.getElementById('api-health-status');
                    const statusMini = document.getElementById('api-status-text-mini');
                    const indicator = healthStatus.querySelector('.status-indicator');
                    const indicatorMini = document.querySelector('#api-status-mini .status-indicator');

                    if (data.status === 'healthy') {
                        healthStatus.innerHTML = '<span class="status-indicator status-good"></span> Healthy';
                        statusMini.textContent = 'Online';
                        indicator.className = 'status-indicator status-good';
                        indicatorMini.className = 'status-indicator status-good';
                    } else {
                        healthStatus.innerHTML = '<span class="status-indicator status-warning"></span> ' + data.status;
                        statusMini.textContent = data.status;
                        indicator.className = 'status-indicator status-warning';
                        indicatorMini.className = 'status-indicator status-warning';
                    }

                    // Update database status
                    const dbStatus = document.getElementById('api-db-status');
                    const dbIndicator = dbStatus.querySelector('.status-indicator');
                    if (data.database === 'connected') {
                        dbStatus.innerHTML = '<span class="status-indicator status-good"></span> Connected';
                    } else {
                        dbStatus.innerHTML = '<span class="status-indicator status-error"></span> ' + (data.database || 'Unknown');
                    }
                })
                .catch(error => {
                    const healthStatus = document.getElementById('api-health-status');
                    const statusMini = document.getElementById('api-status-text-mini');
                    const indicator = healthStatus.querySelector('.status-indicator');
                    const indicatorMini = document.querySelector('#api-status-mini .status-indicator');

                    healthStatus.innerHTML = '<span class="status-indicator status-error"></span> Error: ' + error.message;
                    statusMini.textContent = 'Offline';
                    indicator.className = 'status-indicator status-error';
                    indicatorMini.className = 'status-indicator status-error';

                    document.getElementById('api-response-time').textContent = 'Failed';
                    document.getElementById('api-db-status').innerHTML = '<span class="status-indicator status-unknown"></span> Unknown';
                });
        }

        // Update timestamp
        function updateTimestamp() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { hour12: false });
            document.getElementById('debug-timestamp').textContent = timeString;
        }

        // === ENHANCED LOGGING FEATURES ===

        // Track network errors
        const networkErrors = [];
        function logNetworkError(error) {
            networkErrors.push({
                message: error.message || error.toString(),
                timestamp: new Date().toLocaleTimeString(),
                stack: error.stack
            });
            updateNetworkErrorsDisplay();
        }

        function updateNetworkErrorsDisplay() {
            const container = document.getElementById('network-errors-list');
            const section = document.getElementById('network-errors-section');

            if (networkErrors.length === 0) {
                section.style.display = 'none';
                return;
            }

            section.style.display = 'block';
            container.innerHTML = networkErrors.map((error, index) => `
        <div style="padding: 5px; border-bottom: 1px solid #eee; background: #fff3cd;">
            <strong>#${networkErrors.length - index} - ${error.timestamp}</strong><br>
            <span style="color: #dc3545;">${error.message}</span>
            ${error.stack ? `<details style="margin-top: 3px;"><summary style="cursor: pointer; font-size: 9px;">Stack Trace</summary><pre style="font-size: 8px; margin: 2px 0; overflow-x: auto;">${error.stack}</pre></details>` : ''}
        </div>
    `).reverse().join('');
        }

        // Display LocalStorage contents
        function updateLocalStorageDisplay() {
            const container = document.getElementById('localstorage-list');
            if (!container) return;

            const items = [];
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                let value = localStorage.getItem(key);
                if (value && value.length > 50) {
                    value = value.substring(0, 50) + '...';
                }
                items.push({ key, value });
            }

            if (items.length === 0) {
                container.innerHTML = '<div style="color: #999; padding: 5px;">No items in LocalStorage</div>';
                return;
            }

            container.innerHTML = items.map(item => `
        <div class="debug-item" style="margin: 2px 0;">
            <span class="debug-label">${item.key}:</span>
            <span class="debug-value" style="font-size: 9px; word-break: break-all;">${item.value}</span>
        </div>
    `).join('');
        }

        // Capture console logs
        const consoleLogs = [];
        const originalConsoleLog = console.log;
        const originalConsoleError = console.error;
        const originalConsoleWarn = console.warn;

        console.log = function (...args) {
            consoleLogs.push({
                type: 'log',
                message: args.map(arg => typeof arg === 'object' ? JSON.stringify(arg) : String(arg)).join(' '),
                timestamp: new Date().toLocaleTimeString()
            });
            updateConsoleLogsDisplay();
            originalConsoleLog.apply(console, args);
        };

        console.error = function (...args) {
            consoleLogs.push({
                type: 'error',
                message: args.map(arg => typeof arg === 'object' ? JSON.stringify(arg) : String(arg)).join(' '),
                timestamp: new Date().toLocaleTimeString()
            });
            updateConsoleLogsDisplay();
            originalConsoleError.apply(console, args);
        };

        console.warn = function (...args) {
            consoleLogs.push({
                type: 'warn',
                message: args.map(arg => typeof arg === 'object' ? JSON.stringify(arg) : String(arg)).join(' '),
                timestamp: new Date().toLocaleTimeString()
            });
            updateConsoleLogsDisplay();
            originalConsoleWarn.apply(console, args);
        };

        function updateConsoleLogsDisplay() {
            const container = document.getElementById('console-logs-list');
            if (!container) return;

            if (consoleLogs.length === 0) {
                container.innerHTML = '<div style="color: #999; padding: 5px;">No console logs yet</div>';
                return;
            }

            const recentLogs = consoleLogs.slice(-10);
            container.innerHTML = recentLogs.map((log) => {
                const bgColor = log.type === 'error' ? '#fff3cd' :
                    log.type === 'warn' ? '#fff9e6' : '#f5f5f5';
                const textColor = log.type === 'error' ? '#dc3545' :
                    log.type === 'warn' ? '#ff9800' : '#333';

                return `
            <div style="padding: 4px; border-bottom: 1px solid #eee; background: ${bgColor};">
                <div style="display: flex; justify-content: space-between; font-size: 9px;">
                    <span style="color: ${textColor}; font-weight: bold;">${log.type.toUpperCase()}</span>
                    <span style="color: #999;">${log.timestamp}</span>
                </div>
                <div style="color: ${textColor}; font-size: 9px; margin-top: 2px; word-break: break-word;">${log.message}</div>
            </div>
        `;
            }).reverse().join('');
        }

        // Calculate page load time
        window.addEventListener('load', function () {
            const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
            const loadTimeElement = document.getElementById('page-load-time');
            if (loadTimeElement) {
                loadTimeElement.textContent = (loadTime / 1000).toFixed(3) + ' seconds';
            }
        });

        // Update LocalStorage on DOMContentLoaded
        document.addEventListener('DOMContentLoaded', function () {
            updateLocalStorageDisplay();
            setInterval(updateLocalStorageDisplay, 5000);
            console.log('🔧 Enhanced Debug Panel loaded');
        });

        // Enhanced error tracking
        window.addEventListener('error', function (event) {
            logNetworkError({
                message: event.message,
                filename: event.filename,
                lineno: event.lineno,
                colno: event.colno,
                stack: event.error?.stack
            });
        });

        // Unhandled promise rejection tracking
        window.addEventListener('unhandledrejection', function (event) {
            logNetworkError({
                message: 'Unhandled Promise Rejection: ' + event.reason,
                stack: event.reason?.stack
            });
        });

    </script>