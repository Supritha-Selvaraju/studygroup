<?php
header('Content-Type: application/json');
// Hardcoded, but can extend to database if needed
echo json_encode([
    ["question"=>"How to join a group?","answer"=>"Navigate to the Courses panel and click 'Join Group'."],
    ["question"=>"Can I leave a group?","answer"=>"Yes, click 'Leave' on the group card in your dashboard."],
    ["question"=>"How do I contact support?","answer"=>"Use the 'Contact Support' button in Support panel."],
    ["question"=>"How is my data protected?","answer"=>"All data is secured and used for academic purposes only."],
]);
