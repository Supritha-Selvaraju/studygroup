<?php
header('Content-Type: application/json');

// 1. Configuration (Set your OpenRouter Key here)
$openai_api_key = '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'; 

// --- REAL API LOGIC ---
$data = json_decode(file_get_contents("php://input"), true);
$query = $data['query'] ?? '';
$subject_code = $data['subject_code'] ?? 'General Studies';

if (empty($query)) {
    echo json_encode(['status' => 'error', 'message' => 'Query cannot be empty.']);
    exit;
}

// 2. Prepare API Request Payload
$model = "mistralai/mistral-7b-instruct:free"; 
$payload = [
    'model' => $model,
    'messages' => [
        [
            'role' => 'system',
            'content' => "You are an AI study assistant for college students. The current study group subject is {$subject_code}. Answer the student's question based on this context."
        ],
        [
            'role' => 'user',
            'content' => $query
        ]
    ]
];

// 3. Execute cURL Request
$ch = curl_init('https://openrouter.ai/api/v1/chat/completions'); 
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer {$openai_api_key}" 
    ],
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => false, // Fix for local dev SSL
    CURLOPT_SSL_VERIFYHOST => 0,      // Fix for local dev SSL
    CURLOPT_VERBOSE => true,          // Add for detailed cURL output (optional, but helpful)
    CURLOPT_FOLLOWLOCATION => true    // Ensure redirects are followed
]);

// **********************************
// ** CRITICAL FIXES: Define variables and capture cURL error **
// **********************************
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch); // CAPTURE THE RAW cURL ERROR MESSAGE
curl_close($ch);
// **********************************

// --- Check for a fundamental cURL execution failure ---
if ($response === false) {
    echo json_encode([
        'status' => 'error',
        'message' => 'cURL Network Error: Could not connect to OpenRouter.',
        'details' => $curl_error, // This will give us the exact network issue
        'http_code' => $http_code
    ]);
    exit;
}

// 4. Process Response (Only runs if $response is not false)
$responseData = json_decode($response, true);

if ($http_code === 200 && isset($responseData['choices'][0]['message']['content'])) {
    $ai_response = trim($responseData['choices'][0]['message']['content']);
    echo json_encode(['status' => 'success', 'response' => $ai_response]);
} else {
    // This runs if the connection succeeds but the API returns an error (400, 401, 403, etc.)
    $api_message = $responseData['error']['message'] ?? 'No API error message.';
    $raw_response_snippet = substr($response, 0, 100);

    echo json_encode([
        'status' => 'error',
        'message' => "API Error ({$http_code}): " . $api_message,
        'curl_error' => $curl_error,
        'raw_api_response_start' => $raw_response_snippet // Shows the start of the error payload
    ]);
}
?>