<?php
// PHP Backend Logic for group_chat.php
require_once('config.php'); // Your DB connection
// NOTE: Use a real session check to get user and validate group membership
$current_user_id = 1; // Mock User ID
$current_user_name = "Jane Doe"; // Mock User Name

$groupId = $_GET['group_id'] ?? null;

if (!$groupId) {
    // Redirect or show an error if group_id is missing
    header('Location: dashboard.php');
    exit;
}

// Sanitize inputs for SQL (CRITICAL FIX: Use intval for integer safety)
$safe_group_id = intval($groupId);
$safe_user_id = intval($current_user_id);

// 1. Fetch Group List (for the sidebar)
$sql_groups = "
    SELECT sg.group_id, sg.group_name, s.subject_code
    FROM study_groups sg
    JOIN group_members gm ON sg.group_id = gm.group_id
    JOIN subjects s ON sg.subject_id = s.subject_id
    WHERE gm.user_id = {$safe_user_id}
    ORDER BY sg.created_at DESC;
";
$group_list_result = $mysqli->query($sql_groups);
$group_list = [];
if ($group_list_result) {
    while ($row = $group_list_result->fetch_assoc()) {
        $group_list[] = $row;
    }
}

// 2. Fetch Initial Group Info (for header)
$sql_current_group = "
    SELECT sg.group_name, s.subject_code
    FROM study_groups sg
    JOIN subjects s ON sg.subject_id = s.subject_id
    WHERE sg.group_id = {$safe_group_id};
";
$current_group_result = $mysqli->query($sql_current_group);
$current_group_info = $current_group_result ? $current_group_result->fetch_assoc() : ['group_name' => 'Unknown Group', 'subject_code' => 'N/A'];
$current_group_name = htmlspecialchars($current_group_info['group_name']);
$current_subject_code = htmlspecialchars($current_group_info['subject_code']);

// 3. Fetch Next Session (for notification banner)
$sql_session = "
    SELECT scheduled_time
    FROM study_sessions
    WHERE group_id = {$safe_group_id}
    ORDER BY scheduled_time ASC
    LIMIT 1;
";
$session_result = $mysqli->query($sql_session);
$next_session = $session_result && $session_result->num_rows > 0 ? $session_result->fetch_assoc() : null;

// Close connection early as we mostly need client-side AJAX now
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $current_group_name; ?> Chat</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* 🎨 Custom Color Theme */
        :root {
            --rec-purple: #6f2da8;
            --rec-dark-purple: #4e1a73;
            --rec-yellow: #fed700;
            --rec-white: #f9f9f9;
            --rec-gray: #252525;
            --background-dark: #1e1e1e;
            --chat-bg: #2d2d2d;
            --text-light: #f0f0f0;
            --border-color: #444;
            --link-color: #90ee90; /* Light green for links */
        }

        /* General Styles */
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background-dark);
            margin: 0;
            color: var(--text-light);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* 1. Sidebar (Group List) */
        .sidebar {
            width: 300px;
            background-color: var(--rec-gray);
            border-right: 1px solid var(--border-color);
            padding: 1rem 0;
            overflow-y: auto;
            flex-shrink: 0;
        }
        .sidebar-header {
            padding: 0 1rem 1rem;
            font-size: 1.2rem;
            font-weight: 700;
            border-bottom: 1px solid var(--border-color);
        }
        .group-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
            text-decoration: none;
            color: inherit;
        }
        .group-item:hover, .group-item.active {
            background-color: var(--border-color);
        }
        .group-item.active {
            border-left: 5px solid var(--rec-yellow);
            padding-left: calc(1rem - 5px);
        }
        .group-name { flex-grow: 1; margin-left: 10px; }
        .group-initials {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--rec-purple);
            color: var(--rec-white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* 2. Main Chat Area */
        .chat-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: var(--chat-bg);
        }

        /* Chat Header (Notifications/Context) */
        .chat-header {
            padding: 1rem 1.5rem;
            background-color: var(--rec-gray);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }
        .chat-title h3 { margin: 0; color: var(--text-light); }
        .chat-title p { margin: 0; font-size: 0.8rem; color: #aaa; }
        .notification-banner {
            background-color: var(--rec-dark-purple);
            color: var(--rec-white);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Message Feed */
        .message-feed {
            flex-grow: 1;
            overflow-y: auto;
            padding: 20px 30px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" opacity="0.1"><circle cx="50" cy="50" r="10" fill="%234e1a73"/></svg>');
            background-repeat: repeat;
        }

        /* Message Bubble Styles */
        .message {
            display: flex;
            margin-bottom: 12px;
            max-width: 80%;
        }
        .message.sent {
            margin-left: auto;
            justify-content: flex-end;
        }
        .message.received {
            margin-right: auto;
        }
        .message-bubble {
            padding: 10px 15px;
            border-radius: 18px;
            word-wrap: break-word;
            position: relative;
        }
        .message-bubble .sender {
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .message-bubble .content {
            font-size: 1rem;
            line-height: 1.3;
        }
        .message-bubble .timestamp {
            display: block;
            text-align: right;
            font-size: 0.7rem;
            color: #999;
            margin-top: 5px;
        }
        .message-bubble .upvotes {
            font-size: 0.8em;
            color: var(--rec-yellow);
            margin-left: 10px;
        }
        /* Sent Message Bubble (Primary Purple) */
        .message.sent .message-bubble {
            background-color: var(--rec-purple);
            color: var(--rec-white);
            border-bottom-right-radius: 2px;
        }
        .message.sent .sender {
            color: var(--rec-yellow);
        }
        /* Received Message Bubble (Dark Gray) */
        .message.received .message-bubble {
            background-color: var(--rec-gray);
            color: var(--text-light);
            border-bottom-left-radius: 2px;
        }
        .message.received .sender {
            color: var(--link-color);
        }

        /* Chat Input Area */
        .chat-input {
            padding: 1rem;
            background-color: var(--rec-gray);
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 10px;
            align-items: center;
            flex-shrink: 0;
        }
        .chat-input input, .chat-input textarea {
            flex-grow: 1;
            padding: 10px 15px;
            border-radius: 25px;
            border: none;
            background-color: var(--chat-bg);
            color: var(--text-light);
            font-size: 1rem;
            resize: none;
        }
        .chat-input button {
            background-color: var(--rec-yellow);
            color: var(--rec-gray);
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.2s;
        }
        .chat-input button:hover {
            background-color: #fce849;
            transform: scale(1.05);
        }
        .chat-input button svg {
            width: 24px;
            height: 24px;
            stroke: var(--rec-gray);
        }
        /* Action buttons in chat bubble */
        .message-actions button {
            background: none;
            border: none;
            color: var(--rec-yellow);
            font-size: 0.75rem;
            cursor: pointer;
            padding: 0 5px;
        }

        /* 3. Resource Sharing Panel (Right Panel) */
        .resource-panel {
            width: 300px;
            background-color: var(--rec-gray);
            border-left: 1px solid var(--border-color);
            flex-shrink: 0;
            overflow-y: auto;
        }
        .resource-panel h4 {
            padding: 1rem;
            margin: 0;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
        }

        /* **AI Button Positioning (Confirmed Correct)** */
.ai-assistant-footer {
  position: fixed; 
  bottom: 100px; /* CORRECTED: Increased to move it above the input footer */
  /* 300px (Resource Panel width) + 20px (margin) = 320px from the right edge of the screen */
  right: 320px; 
  z-index: 10000; 
  width: 54px;
  height: 54px;
  border-radius: 50%;
  background-color: var(--rec-purple);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4); 
  cursor: pointer;
  transition: background-color 0.2s;
}

        .ai-assistant-footer:hover {
            background-color: var(--rec-dark-purple);
        }

        .ai-assistant-footer button {
            width: 100%;
            height: 100%;
            border: none;
            background: transparent;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .ai-assistant-footer svg {
            stroke: var(--rec-white);
            width: 26px;
            height: 26px;
        }

        .resource-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px dashed #333;
            font-size: 0.9rem;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .resource-item .details strong {
            display: block;
            color: var(--rec-yellow);
            margin-bottom: 5px;
        }
        .resource-item .details span {
            display: block;
            color: #aaa;
            font-size: 0.75rem;
            margin-top: 2px;
        }
        .resource-item .actions a {
            color: var(--link-color);
            text-decoration: none;
            margin-top: 5px;
            font-size: 0.85rem;
        }

        /* Modal for Upload */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .modal.active { display: flex; }
        .modal-content {
            background: var(--rec-gray);
            padding: 2rem;
            border-radius: 12px;
            width: 350px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            color: var(--text-light);
        }
        /* NEW: AI Modal Specific Layout (Overriding default .modal-content) */
#ai-chat-modal .modal-content {
    width: 400px;
    max-width: 95vw;
    max-height: 80vh;
    padding: 0;
    display: flex;
    flex-direction: column;
    box-sizing: border-box;
}

        
        .modal-content label { display: block; margin-bottom: 5px; font-weight: 500;}
        .modal-content input[type="text"], .modal-content textarea, .modal-content select {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            box-sizing: border-box;
            background-color: var(--chat-bg);
            color: var(--text-light);
        }
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 1rem;
        }
        .modal-actions button {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .modal-actions .cancel {
            background-color: #555;
            color: var(--rec-white);
            border: none;
        }
        .modal-actions .primary {
            background-color: var(--rec-purple);
            color: var(--rec-white);
            border: none;
        }
        .modal-actions .primary:hover {
            background-color: var(--rec-dark-purple);
        }

        /* Icon Utility */
        .icon {
            display: inline-block;
            vertical-align: middle;
            margin-right: 5px;
        }
        .icon-small { width: 18px; height: 18px; }
        /* AI Chat Specific Styles */
        .ai-message {
            margin-bottom: 10px;
            padding: 8px 12px;
            border-radius: 10px;
            max-width: 90%;
            word-wrap: break-word;
            line-height: 1.4;
        }
        .ai-message.user {
            background-color: #5d5d5d; /* Dark gray for user input */
            margin-left: auto;
            color: var(--rec-white);
        }
        .ai-message.assistant {
            background-color: var(--rec-dark-purple); /* Dark purple for AI */
            margin-right: auto;
            color: var(--text-light);
        }
        .ai-message strong {
            display: block;
            font-size: 0.75rem;
            color: var(--rec-yellow);
            margin-bottom: 3px;
        }
        
        /* Modal Header Style (for AI Assistant title bar) */
        #ai-chat-modal .modal-content h3 {
            background-color: var(--rec-purple);
            padding: 10px 15px;
            margin: 0;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            color: var(--rec-white) !important;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        #ai-chat-feed {
    flex-grow: 1;
    overflow-y: auto;
    height: 340px;   /* Fix the height as desired (adjust for your modal's needs) */
    min-height: 200px;    /* Optional: minimum height for initial empty state */
    max-height: 340px;    /* Ensures vertical scroll if chat messages exceed this */
    padding: 12px;
    border: 1px solid var(--rec-purple);
    border-radius: 8px;
    margin-bottom: 18px;
    background-color: var(--chat-bg);
    color: var(--text-light);
}

    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-header">
        Study Groups 📚
    </div>
    <div id="group-list">
        <?php foreach ($group_list as $g): ?>
            <?php
                $isActive = $g['group_id'] == $groupId;
                $initials = strtoupper(substr($g['subject_code'], 0, 2));
            ?>
            <a href="group_chat.php?group_id=<?php echo $g['group_id']; ?>" 
                class="group-item <?php echo $isActive ? 'active' : ''; ?>"
                data-group-id="<?php echo $g['group_id']; ?>">
                <div class="group-initials"><?php echo $initials; ?></div>
                <div class="group-name"><?php echo htmlspecialchars($g['group_name']); ?></div>
            </a>
        <?php endforeach; ?>
    </div>
</aside>

<div class="chat-container">

    <header class="chat-header">
        <div class="chat-title">
            <h3><?php echo $current_group_name; ?> Chat</h3>
            <p><?php echo $current_subject_code; ?> | Members Online: <span id="member-count">...</span></p>
        </div>
        <div class="notification-banner">
            <svg class="icon icon-small" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <span id="upcoming-event-msg">
                <?php
                    if ($next_session) {
                        // Format the date for display (Client-side JS will handle better formatting)
                        echo 'Next Session: ' . date('D, M j \@ h:i A', strtotime($next_session['scheduled_time']));
                    } else {
                        echo 'No upcoming sessions scheduled.';
                    }
                ?>
            </span>
        </div>
    </header>

    <main id="message-feed" class="message-feed">
        <p style="text-align: center; color: #777;">Loading messages...</p>
    </main>

    <footer class="chat-input">
        <textarea id="message-input" placeholder="Type a message, question or doubt..." rows="1"></textarea>
        <button id="resource-button" onclick="openResourceModal()" title="Share File/Resource">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
        </button>
        <button id="send-button" onclick="sendMessage()" title="Send Message">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        </button>
    </footer>

</div>

<aside class="resource-panel">
    <h4>Shared Resources & Files 📁</h4>
    <div id="resources-list">
        <p style="text-align: center; color: #777; padding: 1rem;">Loading resources...</p>
    </div>
    <div class="ai-assistant-footer" onclick="openAIChatModal()">
    <button>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <rect x="3" y="7" width="18" height="10" rx="4" />
            <path d="M8 15h8M12 19v-4" />
        </svg>
    </button>
</div>
    </aside>


<div id="resource-modal" class="modal">
    <div class="modal-content">
        <h3 style="margin-top:0;">Upload Resource for <?php echo $current_subject_code; ?></h3>
        <p style="color: #ccc; font-size: 0.9em;">*In a real deployment, a file input would replace the text path.</p>
        
        <label for="resource-path">File Path / Link (Mock)</label>
        <input type="text" id="resource-path" placeholder="e.g., /uploads/my_notes.pdf or https://link.com">

        <label for="resource-description">Description</label>
        <input type="text" id="resource-description" placeholder="Briefly describe the content">

        <label for="resource-type">Type</label>
        <select id="resource-type">
            <option value="Note">Note/Summary (PDF/DOCX)</option>
            <option value="Link">External Link</option>
            <option value="Slides">Presentation Slides (PPTX)</option>
            <option value="Assignment">Assignment/Test</option>
        </select>
        <div class="modal-actions">
            <button class="cancel" onclick="closeResourceModal()">Cancel</button>
            <button class="primary" onclick="uploadResource()">
                <svg class="icon icon-small" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Upload
            </button>
        </div>
    </div>
</div>

<div id="ai-chat-modal" class="modal">
    <div class="modal-content" style="width: 400px; max-width: 95vw; max-height: 80vh; display: flex; flex-direction: column; padding: 0;"> 
        
        <h3 style="padding: 15px; margin: 0; background-color: var(--rec-purple); color: var(--rec-white); font-weight: 700; border-top-left-radius: 12px; border-top-right-radius: 12px; display: flex; justify-content: space-between; align-items: center;">
            AI Assistant - <?php echo $current_subject_code; ?>
            <button onclick="closeAIChatModal()" style="background: none; border: none; color: var(--rec-white); font-size: 1.5rem; line-height: 1; cursor: pointer; padding: 0;">&times;</button>
        </h3>
        
        <div style="padding: 16px; flex-grow: 1; display: flex; flex-direction: column;">
            <div id="ai-chat-feed" style="flex-grow: 1; overflow-y: auto; padding: 12px; border: 1px solid var(--rec-purple); border-radius: 8px; margin-bottom: 18px; background-color: var(--chat-bg); color: var(--text-light);">
                <div class="ai-message assistant">
                    <strong>Assistant</strong><br/>
                    Hello! I'm your AI Study Assistant for <strong><?php echo $current_subject_code; ?></strong>. Ask me to clarify concepts or clear doubts!
                </div>
            </div>

            <div class="ai-input-container" style="display: flex; gap: 12px; align-items: center;">
                <input type="text" id="ai-query-input" placeholder="Type your message..." style="flex-grow: 1; border-radius: 25px; padding: 12px 20px; border: 2px solid var(--border-color); background-color: var(--chat-bg); color: var(--text-light);" />
                <button id="ai-send-button" onclick="sendAIAssistantQuery()" style="background-color: var(--rec-yellow); color: var(--rec-gray); border-radius: 50%; width: 44px; height: 44px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"/>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                    </svg>
                </button>
            </div>

            <div class="modal-actions" style="display: flex; justify-content: flex-start; margin-top: 16px;">
                <button class="cancel" onclick="closeAIChatModal()" style="background-color: var(--rec-purple); color: var(--rec-white); border: none; border-radius: 8px; padding: 8px 18px; cursor: pointer; font-weight: 600;">
                    Close Chat
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    // --- Global Configuration ---
    const API_URL = 'api/chat_actions.php';
    const CURRENT_GROUP_ID = <?php echo $safe_group_id; ?>; // Use sanitized variable
    const CURRENT_USER_ID = <?php echo $safe_user_id; ?>; // Use sanitized variable
    const CURRENT_USER_NAME = "<?php echo $current_user_name; ?>";
    const CURRENT_SUBJECT_CODE = "<?php echo $current_subject_code; ?>";
    
    let isFetchingMessages = false;
    
    // --- Core UI Functions ---

    /** Scrolls the chat feed to the very bottom. */
    function scrollToBottom() {
        const feed = document.getElementById('message-feed');
        if (feed) {
             feed.scrollTop = feed.scrollHeight;
        }
    }

    /** Helper to scroll AI feed */
    function scrollToAIBottom() {
        const feed = document.getElementById('ai-chat-feed');
        if (feed) {
             feed.scrollTop = feed.scrollHeight;
        }
    }

    /** Renders the message feed with data fetched from the backend. */
    function renderMessages(messages) {
        const feed = document.getElementById('message-feed');
        if (!feed) return;

        feed.innerHTML = messages.map(m => {
            const isSent = m.user_id == CURRENT_USER_ID; 
            const statusClass = isSent ? 'sent' : 'received';
            const senderTag = isSent ? 'You' : m.user_name;
            const time = new Date(m.timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            const textWithLinks = m.message_text.replace(/(https?:\/\/[^\s]+)/g, (url) => {
                return `<a href="${url}" target="_blank" style="color: var(--link-color); text-decoration: underline;">${url.substring(0, 30)}...</a>`;
            });

            return `
                <div class="message ${statusClass}">
                    <div class="message-bubble">
                        <div class="sender">${senderTag}</div>
                        <div class="content">${textWithLinks.replace(/\n/g, '<br>')}</div>
                        <span class="timestamp">${time}</span>
                        <div class="message-actions" style="margin-top: 5px; text-align: right;">
                             <button onclick="replyToMessage('${m.user_name}')">Reply</button> 
                             <button onclick="toggleUpvote(${m.message_id})">
                                 👍 ${m.upvote_count}
                             </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        scrollToBottom();
    }

    /** Renders the resources panel with data fetched from the backend. */
    function renderResources(resources) {
        const list = document.getElementById('resources-list');
        if (!list) return;

        if (resources.length === 0) {
            list.innerHTML = `<p style="text-align: center; color: #777; padding: 1rem;">No shared resources yet.</p>`;
            return;
        }

        list.innerHTML = resources.map(r => `
            <div class="resource-item">
                <div class="details">
                    <strong>${r.file_path.split('/').pop() || 'Untitled File'}</strong>
                    <span>Type: ${r.file_type} | Uploader: ${r.uploaded_by}</span>
                    <span>Desc: ${r.description.substring(0, 50)}</span>
                </div>
                <div class="actions">
                    <a href="api/download_resource.php?id=${r.material_id}">Download</a>
                </div>
            </div>
        `).join('');
    }

    // --- Data Fetching & Posting (AJAX) ---

    async function fetchMessages() {
        if (isFetchingMessages) return;
        isFetchingMessages = true;

        try {
            const response = await fetch(`${API_URL}?action=fetch_messages&group_id=${CURRENT_GROUP_ID}`);
            const result = await response.json();

            if (result.status === 'success') {
                renderMessages(result.data);
            } else {
                console.error("Fetch Messages Error:", result.message);
                document.getElementById('message-feed').innerHTML = `<p style="color: red; text-align: center;">Error loading messages: ${result.message}</p>`;
            }
        } catch (error) {
            console.error('Network Error:', error);
            document.getElementById('message-feed').innerHTML = `<p style="color: red; text-align: center;">Network error while fetching messages.</p>`;
        } finally {
            isFetchingMessages = false;
        }
    }

    async function fetchResources() {
        try {
            const response = await fetch(`${API_URL}?action=fetch_resources&group_id=${CURRENT_GROUP_ID}`);
            const result = await response.json();

            if (result.status === 'success') {
                renderResources(result.data);
            } else {
                console.error("Fetch Resources Error:", result.message);
            }
        } catch (error) {
            console.error('Network Error:', error);
        }
    }

    async function sendMessage() {
        const input = document.getElementById('message-input');
        const text = input.value.trim();

        if (text) {
            input.disabled = true;

            const body = {
                action: 'send_message',
                group_id: CURRENT_GROUP_ID,
                message_text: text
            };

            try {
                const response = await fetch(`${API_URL}?group_id=${CURRENT_GROUP_ID}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(body)
                });
                const result = await response.json();

                if (result.status === 'success') {
                    await fetchMessages();
                } else {
                    alert('Failed to send message: ' + result.message);
                }
            } catch (error) {
                console.error('Network Error:', error);
                alert('A network error occurred while sending the message.');
            } finally {
                input.value = '';
                input.disabled = false;
                input.style.height = 'auto'; // Reset height
                input.focus();
            }
        }
    }

    async function toggleUpvote(messageId) {
        const body = {
            action: 'toggle_upvote',
            group_id: CURRENT_GROUP_ID,
            message_id: messageId
        };
        
        try {
            const response = await fetch(`${API_URL}?group_id=${CURRENT_GROUP_ID}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(body)
            });
            const result = await response.json();
            
            if (result.status === 'success') {
                console.log(result.message);
                await fetchMessages();
            } else {
                alert('Upvote action failed: ' + result.message);
            }
        } catch (error) {
            console.error('Network Error:', error);
            alert('A network error occurred while processing the upvote.');
        }
    }

    async function uploadResource() {
        const filePath = document.getElementById('resource-path').value.trim();
        const description = document.getElementById('resource-description').value.trim();
        const fileType = document.getElementById('resource-type').value;

        if (!filePath || !description) {
            alert('Please provide a path/link and a description.');
            return;
        }
        
        const modalButtons = document.querySelectorAll('#resource-modal .modal-actions button');
        modalButtons.forEach(btn => btn.disabled = true);

        const body = {
            action: 'upload_resource',
            group_id: CURRENT_GROUP_ID,
            file_path: filePath,
            file_type: fileType,
            description: description
        };

        try {
            const response = await fetch(`${API_URL}?group_id=${CURRENT_GROUP_ID}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(body)
            });
            const result = await response.json();

            if (result.status === 'success') {
                closeResourceModal();
                fetchResources(); // Refresh the resources list
            } else {
                alert('Upload failed: ' + result.message);
            }
        } catch (error) {
            console.error('Network Error:', error);
            alert('A network error occurred during the upload.');
        } finally {
            modalButtons.forEach(btn => btn.disabled = false);
        }
    }
    
    // --- UI/Modal Handlers ---

    window.replyToMessage = function(senderName) {
        const input = document.getElementById('message-input');
        input.value = `@${senderName} `;
        input.focus();
    }

    window.openResourceModal = function() {
        document.getElementById('resource-modal').classList.add('active');
    }

    window.closeResourceModal = function() {
        document.getElementById('resource-modal').classList.remove('active');
        document.getElementById('resource-path').value = '';
        document.getElementById('resource-description').value = '';
    }
    
    // --- AI Assistant Functions (NEW) ---
    
    function openAIChatModal() {
        document.getElementById('ai-chat-modal').classList.add('active');
        document.getElementById('ai-query-input').focus(); 
        scrollToAIBottom();
    }

    function closeAIChatModal() {
        document.getElementById('ai-chat-modal').classList.remove('active');
    }

    async function sendAIAssistantQuery() {
        const input = document.getElementById('ai-query-input');
        const sendBtn = document.getElementById('ai-send-button');
        const query = input.value.trim();
        const feed = document.getElementById('ai-chat-feed');

        if (!query) return;
        
        input.disabled = true;
        sendBtn.disabled = true;

        feed.innerHTML += `<div class="ai-message user"><strong>You</strong>${query}</div>`;
        input.value = '';
        scrollToAIBottom();

        const loadingMessage = document.createElement('div');
        loadingMessage.className = 'ai-message assistant';
        loadingMessage.id = 'ai-loading';
        loadingMessage.innerHTML = '<strong>Assistant</strong>Thinking...';
        feed.appendChild(loadingMessage);
        scrollToAIBottom();

        const body = {
            group_id: CURRENT_GROUP_ID,
            subject_code: CURRENT_SUBJECT_CODE,
            query: query
        };

        try {
            const response = await fetch('api/chat_bot.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(body)
            });
            const result = await response.json();
            
            loadingMessage.remove(); 
            
            let aiResponseText = result.status === 'success' ? result.response : 
                                 'Sorry, the AI connection failed. ' + (result.message || 'Check your API key/backend setup.');

            feed.innerHTML += `<div class="ai-message assistant"><strong>Assistant</strong>${aiResponseText}</div>`;

        } catch (error) {
            console.error('AI Network Error:', error);
            if(loadingMessage) loadingMessage.innerHTML = '<strong>Assistant</strong>Critical connection error.';
        } finally {
            input.disabled = false;
            sendBtn.disabled = false;
            input.focus();
            scrollToAIBottom();
        }
    }

    // Bind new functions to the global scope
    window.openAIChatModal = openAIChatModal;
    window.closeAIChatModal = closeAIChatModal;
    window.sendAIAssistantQuery = sendAIAssistantQuery;
    
    // --- Initialization and Real-time Mock ---

    function initChat() {
        fetchMessages();
        fetchResources();
        
        document.getElementById('member-count').textContent = Math.floor(Math.random() * 10) + 5; 

        setInterval(fetchMessages, 5000); 
    }

    document.addEventListener('DOMContentLoaded', () => {
        initChat();

        const input = document.getElementById('message-input');
        input.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        }, false);
        
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
        
        // AI Input Enter Key Handler
        document.getElementById('ai-query-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendAIAssistantQuery();
            }
        });
    });

</script>

</body>
</html>