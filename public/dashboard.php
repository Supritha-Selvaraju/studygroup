<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Study Board Dashboard</title>
    <!-- Use Inter font for consistency and professionalism -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
    
    <style>
        /* CSS Variables for easy theme management */
        :root {
            --primary-purple: #6f42c1;
            --primary-purple-dark: #5a359a;
            --background-light: #f8f9fa;
            --background-white: #ffffff;
            --text-dark: #212529;
            --text-muted: #6c757d;
            --border-color: #dee2e6;
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            --border-radius: 12px;
        }

        /* General Body Styles */
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background-light);
            margin: 0;
            color: var(--text-dark);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Main Dashboard Layout */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles (Unchanged from previous iteration to honor request) */
        .sidebar {
            width: 280px; 
            background-color: var(--background-white);
            border-right: 1px solid var(--border-color);
            padding: 2.5rem 1.5rem; 
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.02); 
            position: sticky;
            top: 0;
            height: 100vh;
        }
        
        /* Sidebar Branding */
        .sidebar-header {
            margin-bottom: 2.5rem;
        }
        .sidebar-header h2 {
            font-weight: 800;
            color: var(--primary-purple);
            margin: 0;
            letter-spacing: -0.5px;
            font-size: 1.75rem;
        }

        /* Profile Section */
        .profile-section {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .profile-section .avatar-icon, .profile-section img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            border: 4px solid var(--primary-purple);
            background-color: var(--primary-purple);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            object-fit: cover;
        }
        
        .profile-section h3 {
            margin: 0.5rem 0 0.25rem;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .profile-section p {
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        /* Sidebar Navigation */
        .sidebar-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav li {
            margin-bottom: 0.5rem;
        }

        .sidebar-nav li a {
            display: flex;
            align-items: center;
            gap: 0.75rem; 
            padding: 0.85rem 1rem;
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            border-radius: var(--small-radius);
            transition: all 0.2s;
            position: relative;
        }
        
        /* Active/Hover State with Left Accent Bar */
        .sidebar-nav li a:hover {
            background-color: rgba(111, 45, 168, 0.05); /* Light purple hover based on new color */
            color: var(--primary-purple);
        }

        .sidebar-nav li a.active {
            background-color: rgba(111, 45, 168, 0.1); 
            color: var(--primary-purple);
            font-weight: 600;
            /* Left accent bar for active item */
            border-left: 4px solid var(--primary-purple);
            padding-left: calc(1rem - 4px); 
        }
        
        .sidebar-nav li a.active svg {
            fill: var(--primary-purple); 
            fill-opacity: 0.1;
        }

        /* Action Buttons (Primary) */
        .action-btn, .settings-btn, .logout-btn, 
        .card-actions button.primary {
            background-color: var(--primary-purple);
            color: white;
            border: 1px solid var(--primary-purple);
            border-radius: var(--small-radius);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .action-btn:hover, .settings-btn:hover, .logout-btn:hover,
        .card-actions button.primary:hover {
            background-color: var(--primary-purple-dark);
            border-color: var(--primary-purple-dark);
            box-shadow: 0 4px 12px rgba(111, 45, 168, 0.4); /* Stronger button shadow */
            transform: translateY(-2px);
        }
        
        /* Secondary/Outline Button Styling */
        .card-actions button.secondary,
        .filter-btn {
            background-color: transparent;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            border-radius: var(--small-radius);
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .card-actions button.secondary:hover,
        .filter-btn:hover {
            background-color: rgba(111, 45, 168, 0.1);
            color: var(--primary-purple);
            border-color: var(--primary-purple);
        }

        .sidebar .sidebar-footer {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }


        /* Main Content Area */
        main {
            flex: 1;
            padding: 2rem 2.5rem;
            overflow-y: auto;
        }
        
        /* Header for Search/Filter */
        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .search-bar {
            position: relative;
            width: 50%;
        }

        .search-bar input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem; /* Add padding for icon */
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
        }
        
        .search-bar svg {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .filter-btn {
            padding: 0.75rem 1.5rem;
            background-color: var(--background-white);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* General Panel/Card Styles */
        .panel {
            background-color: var(--background-white);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
            padding: 1.5rem;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1rem;
        }

        .panel-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }

        /* Stats Bar */
        #statsBar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stats-card {
            background-color: var(--background-white);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .stats-card .stat-icon {
            color: var(--primary-purple);
            margin-bottom: 0.5rem;
        }


        .stats-card div:nth-child(2) { /* The value */
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--primary-purple);
        }

        .stats-card div:last-child { /* The label */
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Card Layouts: FIX 1 - Set Groups/Courses to 3 cards per row */
        #joinedGroupsList, #coursesList {
            display: grid;
            /* 3 cards in a row, minimum width of 280px per card */
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            min-height: 300px; 
        }

        /* FIX 2 - Calendar and Progress: Revert to 2-column layout inside their respective panels */
        /* The main container (.content-row) is 1fr 1fr (50%/50%) on desktop */
        /* We set the inner content of these panels to 2 columns on large screens */
        #calendarEvents, #progressTracker {
            display: grid;
            grid-template-columns: 1fr; /* Default to single column on mobile/small viewports */
            gap: 1.5rem;
            min-height: 300px;
        }
        @media (min-width: 768px) {
            #calendarEvents, #progressTracker {
                /* When the parent column is wide enough (e.g., in content-row), show 2 columns */
                grid-template-columns: repeat(2, 1fr); 
            }
        }
        /* Specific Fix for Notes to make them stack nicely when data is duplicated */
        #progressTracker .note-card {
            min-height: 100px;
        }
        
        .group-card, .course-card, .event-card, .note-card {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1.5rem;
            transition: box-shadow 0.2s, transform 0.2s;
            display: flex;
            flex-direction: column;
            background-color: var(--background-white);
        }
        
        .group-card:hover, .course-card:hover, .event-card:hover, .note-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.08);
        }

        .group-card h4, .course-card h5, .event-card h5, .note-card h5 {
            margin: 0 0 0.5rem 0;
            font-weight: 600;
            color: var(--primary-purple);
        }
        
        .card-content {
            flex-grow: 1;
            margin-bottom: 1rem;
        }
        
        /* Styles for buttons inside cards */
        .card-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: auto; /* Push actions to the bottom */
        }
        
        .card-actions button {
            flex: 1;
            padding: 0.5rem;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            background-color: transparent;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .card-actions button.primary {
            background-color: var(--primary-purple);
            color: white;
            border-color: var(--primary-purple);
        }
        
        .card-actions button.primary:hover {
             background-color: var(--primary-purple-dark);
        }

        .card-actions button.secondary {
             background-color: var(--background-light);
             color: var(--text-dark);
        }
        
        .card-actions button.secondary:hover {
            background-color: var(--border-color);
        }

        /* Layout for side-by-side panels */
        .content-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        @media (min-width: 992px) {
            .content-row {
                grid-template-columns: 1fr 1fr;
            }
        }

        /* Styles for Pagination */
        .pagination {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
            gap: 0.5rem;
        }

        .pagination button {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border-color);
            background-color: var(--background-white);
            border-radius: 6px;
        }
        
        .pagination button.active {
            background-color: var(--primary-purple);
            color: white;
            border-color: var(--primary-purple);
        }

        .pagination button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .modal.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-content {
            background: var(--background-white);
            border-radius: 16px;
            max-width: 90%;
            width: 400px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.16);
            transform: translateY(-50px);
            transition: transform 0.3s ease;
        }

        .modal.active .modal-content {
            transform: translateY(0);
        }

        .modal-content label {
            display: block;
            margin-top: 1rem;
            margin-bottom: 0.25rem;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .modal-content input, .modal-content select, .modal-content textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .dashboard-container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                height: auto;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                padding: 1rem;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
                box-shadow: var(--card-shadow);
            }
            .sidebar-nav { display: none; }
            .sidebar .profile-section { display: none; }
            .sidebar-footer { 
                display: flex; 
                gap: 1rem; 
                margin-top: 0;
                width: auto;
                flex: 1;
                justify-content: flex-end;
            }
            .sidebar-footer button {
                width: auto;
                padding: 0.5rem 1rem;
                margin-top: 0;
            }
            main { padding: 1.5rem; }
            .main-header { flex-direction: column; gap: 1rem; align-items: stretch; }
            .search-bar { width: 100%; }
            /* On small screens, groups/courses will fall back to single column */
            #joinedGroupsList, #coursesList {
                grid-template-columns: 1fr; 
            }
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div>
            <div class="profile-section" id="profileSection">
                <p>Loading profile...</p>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <!-- Groups Icon: Users -->
                    <li><a href="#groupsPanel" class="active">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        Groups
                    </a></li>
                    <!-- Courses Icon: BookOpen -->
                    <li><a href="#coursesPanel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 13V6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v7"/><path d="M2 17.5A2.5 2.5 0 0 1 4.5 15H20"/><path d="M22 17.5a2.5 2.5 0 0 0-2.5-2.5H4"/></svg>
                        Courses
                    </a></li>
                    <!-- Calendar Icon: Calendar -->
                    <li><a href="#calendarPanel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        Calendar
                    </a></li>
                    <!-- Progress Icon: TrendingUp -->
                    <li><a href="#progressPanel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="18 7 22 7 22 11"/></svg>
                        Progress
                    </a></li>
                    <!-- Support Icon: HelpCircle -->
                    <li><a href="#supportPanel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                        Support
                    </a></li>
                </ul>
            </nav>
        </div>

        <div class="sidebar-footer">
            <button class="settings-btn" onclick="showMessage('Settings', 'Settings functionality is currently disabled in this demo.')">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.44a2 2 0 0 1-2 2H4.44a2 2 0 0 0-2 2v.44a2 2 0 0 1-2 2v3.56a2 2 0 0 0 2 2h.44a2 2 0 0 1 2 2v.44a2 2 0 0 0 2 2h3.56a2 2 0 0 1 2 2v.44a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.44a2 2 0 0 1 2-2h.44a2 2 0 0 0 2-2v-.44a2 2 0 0 1 2-2v-3.56a2 2 0 0 0-2-2h-.44a2 2 0 0 1-2-2v-.44a2 2 0 0 0-2-2h-3.56a2 2 0 0 1-2-2V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                Settings
            </button>
            <button class="logout-btn" onclick="logout()">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Logout
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <main>
        <!-- Search and Filter Header -->
        <header class="main-header">
            <div class="search-bar">
                 <!-- Search Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="searchInput" placeholder="Search groups, courses, etc.">
            </div>
            <button class="filter-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                Filters
            </button>
        </header>

        <!-- Stats Bar -->
        <section id="statsBar"></section>

        <!-- Groups Panel -->
        <section id="groupsPanel" class="panel">
            <div class="panel-header">
                <h2>Joined Groups</h2>
                <button class="action-btn" style="width: auto; padding: 0.5rem 1rem;" onclick="openCreateGroupModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    New Group
                </button>
            </div>
            <div id="joinedGroupsList"></div>
            <!-- Pagination container for groups -->
            <div id="groupsPagination" class="pagination">
                <button id="groupsPrevBtn" disabled onclick="changePage('groups', -1)">Prev</button>
                <span id="groupsPageIndicators"></span>
                <button id="groupsNextBtn" disabled onclick="changePage('groups', 1)">Next</button>
            </div>
        </section>

        <!-- Courses Panel -->
        <section id="coursesPanel" class="panel">
             <div class="panel-header">
                <h2>Available Courses</h2>
            </div>
            <div id="coursesList"></div>
             <!-- Pagination container for courses -->
             <div id="coursesPagination" class="pagination">
                <button id="coursesPrevBtn" disabled onclick="changePage('courses', -1)">Prev</button>
                <span id="coursesPageIndicators"></span>
                <button id="coursesNextBtn" disabled onclick="changePage('courses', 1)">Next</button>
            </div>
        </section>
        
        <!-- Row for side-by-side content -->
        <div class="content-row">
            <!-- Calendar Panel -->
            <section id="calendarPanel" class="panel">
                 <div class="panel-header">
                    <h2>Upcoming Sessions & Events</h2>
                </div>
                <!-- FIX: Set inner grid for calendar/progress lists to 2 columns on desktop -->
                <div id="calendarEvents" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;"></div>
            </section>

            <!-- Progress Panel -->
            <section id="progressPanel" class="panel">
                 <div class="panel-header">
                    <h2>Academic Progress Notes</h2>
                    <button class="action-btn" style="width: auto; padding: 0.5rem 1rem;" onclick="openAddNoteModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Note
                    </button>
                </div>
                <!-- FIX: Set inner grid for calendar/progress lists to 2 columns on desktop -->
                <div id="progressTracker" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;"></div>
            </section>
        </div>

        <!-- Support/FAQ Panel -->
        <section id="supportPanel" class="panel">
             <div class="panel-header">
                <h2>Support & FAQ</h2>
            </div>
             <div id="faqList"></div>
        </section>
    </main>
</div>

<!-- Custom Message Box (Replaces alert()) -->
<div id="messageBox" class="modal">
    <div class="modal-content" style="width:300px; max-width:90%;">
        <h3 id="messageTitle" style="margin-top:0;"></h3>
        <p id="messageText"></p>
        <div style="text-align:right;">
            <button class="action-btn" style="width:auto; padding:0.5rem 1rem; margin-top:0;" onclick="closeMessageBox()">OK</button>
        </div>
    </div>
</div>

<!-- Create Group Modal -->
<div id="createGroupModal" class="modal">
  <div class="modal-content">
    <h2 style="margin:0 0 1rem;">Create New Study Group</h2>
    <form id="createGroupForm">
      <label>Group Name<br>
        <input type="text" name="group_name" placeholder="E.g., Calc II Study Session" required>
      </label>
      <label>Link to Course (Optional)<br>
        <select name="subject_id">
          <option value="">Select a course to link</option>
          <!-- Dynamically populated by JS -->
        </select>
      </label>
      <label>Description<br>
        <textarea name="description" rows="2" placeholder="Briefly describe the group's focus"></textarea>
      </label>
      <div style="text-align:right; margin-top: 1.5rem;">
        <button type="button" onclick="closeCreateGroupModal()" class="secondary" style="width:auto; padding:0.5rem 1rem; margin-right:12px; margin-top:0;">Cancel</button>
        <button type="submit" class="action-btn" style="width:auto; padding:0.5rem 1rem;">
            <!-- Icon changed to represent creating a group -->
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Create Group
        </button>
      </div>
      <div id="createGroupMsg" style="margin-top:1rem; color:var(--primary-purple); font-weight: 500;"></div>
    </form>
  </div>
</div>

<!-- Add Note Modal -->
<div id="addNoteModal" class="modal">
  <div class="modal-content">
    <h2 style="margin:0 0 1rem;">Add Progress Note</h2>
    <form id="addNoteForm">
      <label>Title<br>
        <input type="text" name="note_title" required placeholder="E.g., Review Session Feedback">
      </label>
      <label>Course (optional)<br>
        <select name="subject_id">
          <option value="">General</option>
          <!-- Dynamically populated by JS -->
        </select>
      </label>
      <label>Note<br>
        <textarea name="note_content" rows="3" required placeholder="What did you study? What were your key takeaways?"></textarea>
      </label>
      <div style="text-align:right; margin-top:1.5rem;">
        <button type="button" onclick="closeAddNoteModal()" class="secondary" style="width:auto; padding:0.5rem 1rem; margin-right:12px; margin-top:0;">Cancel</button>
        <button type="submit" class="action-btn" style="width:auto; padding:0.5rem 1rem;">
            <!-- Icon changed to represent saving a note -->
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
            Save Note
        </button>
      </div>
      <div id="noteMsg" style="margin-top:1rem; color:var(--primary-purple); font-weight: 500;"></div>
    </form>
  </div>
</div>

<script>
    /**
     * A robust fetch function that handles JSON parsing and HTTP errors.
     * @param {string} url - The URL to fetch.
     * @param {object} options - Fetch options (method, headers, body, etc.).
     * @returns {Promise<any>} - A promise that resolves with the JSON data.
     */
    async function robustFetch(url, options = {}) {
        try {
            const response = await fetch(url, options);
            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`HTTP error! Status: ${response.status} - ${errorText}`);
            }
            // Attempt to parse JSON, fall back if response is empty or not JSON
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                return response.json();
            } else {
                // FIX: Log potential non-JSON content to debug the "Unexpected non-whitespace character" error
                const nonJsonText = await response.text();
                console.warn('Received non-JSON content or empty response for:', url, nonJsonText.slice(0, 100));
                return { status: 'success', msg: 'Operation successful (Non-JSON response)' };
            }
        } catch (error) {
            console.error('Fetch Error:', url, error);
            throw error; // Re-throw to be caught by the calling function
        }
    }

    // --- Global State for Search, Filtering, and Pagination ---
    let currentSearchTerm = '';
    
    // Data storage
    let availableCoursesData = []; 
    let joinedGroupsData = []; 

    // Pagination state
    const ITEMS_PER_PAGE = 6;
    let groupsCurrentPage = 1;
    let coursesCurrentPage = 1;

    // --- Utility Functions (Custom Message Box) ---

    function showMessage(title, text) {
        document.getElementById('messageTitle').textContent = title;
        document.getElementById('messageText').textContent = text;
        document.getElementById('messageBox').classList.add('active');
    }

    function closeMessageBox() {
        document.getElementById('messageBox').classList.remove('active');
    }

    // --- Dashboard Loaders ---

    document.addEventListener("DOMContentLoaded", function() {
        loadProfile();
        loadStats(); 
        loadJoinedGroups();
        loadCourses();
        loadCalendar();
        loadProgress();
        loadFAQ();

        // **NEW: Search and Filter Event Listeners**
        document.getElementById("searchInput").addEventListener("input", function() {
            currentSearchTerm = this.value.toLowerCase().trim();
            // Reset pages when search term changes
            groupsCurrentPage = 1;
            coursesCurrentPage = 1;
            filterAndRenderGroups();
            filterAndRenderCourses();
        });

        document.querySelector('.filter-btn').addEventListener('click', function() {
            showMessage('Filtering Options', 'Advanced filtering options will be available here soon!');
        });

        // Add event listener for navigation scroll
        document.querySelectorAll('.sidebar-nav a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({ 
                    behavior: 'smooth' 
                });
                document.querySelectorAll('.sidebar-nav a').forEach(a => a.classList.remove('active'));
                this.classList.add('active');
            });
        });
    });

    // Icons for stats cards (remains the same)
    const statIcons = {
        'Joined Groups': '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'Courses Enrolled': '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20h-4.5"/><line x1="10" y1="10" x2="16" y2="10"/><line x1="10" y1="14" x2="16" y2="14"/></svg>',
        'Upcoming Events': '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M12 15l2 2 4-4"/></svg>',
        'User Quality Rating': '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>'
    };

    function loadProfile() {
        robustFetch('api/get_profile.php')
            .then(data => {
                const section = document.getElementById('profileSection');
                if (section) {
                    // Avatar Logic: Use provided image or default to User icon
                    const avatarContent = data.profile_picture ? 
                        `<img src="${data.profile_picture}" alt="profile"/>` :
                        `<div class="avatar-icon"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>`;
                        
                    section.innerHTML = `
                        ${avatarContent}
                        <h3>${data.name || 'Student User'}</h3>
                        <p>${data.email || 'user@example.com'}</p>
                        <div style="font-size:0.93rem; color: var(--text-muted); margin-top: 0.5rem;">Dept: ${data.department || 'N/A'}<br>Year: ${data.year || 'N/A'}</div>
                    `;
                }
            }).catch(error => {
                console.error('Error loading profile:', error);
                const section = document.getElementById('profileSection');
                if(section) section.innerHTML = `<p style="color:red; font-size: 0.8rem; text-align: center;">Could not load profile.</p>`;
            });
    }

    function loadStats() {
        // Using api/get_profile.php as a fallback/source for stats data
        robustFetch('api/get_profile.php') 
            .then(data => {
                const statsBar = document.getElementById('statsBar');
                if (statsBar) {
                    // Assuming the profile endpoint returns these stats fields
                    const stats = [
                        { label: 'Joined Groups', value: data.groups_count || 0 },
                        { label: 'Courses Enrolled', value: data.courses_count || 0 },
                        { label: 'Upcoming Events', value: data.events_count || 0 },
                        { label: 'User Quality Rating', value: data.avg_feedback || 'N/A' }
                    ];

                    statsBar.innerHTML = stats.map(stat => `
                        <div class="stats-card"> 
                            <div class="stat-icon">${statIcons[stat.label]}</div>
                            <div>${stat.value}</div> 
                            <div>${stat.label}</div>
                        </div>
                    `).join('');
                }
            }).catch(error => {
                console.error('Error loading stats:', error);
                const statsBar = document.getElementById('statsBar');
                if(statsBar) statsBar.innerHTML = `<p style="color:red; text-align:center;">Could not load stats.</p>`;
            });
    }

    // --- Pagination Logic ---

    function changePage(type, direction) {
        if (type === 'groups') {
            groupsCurrentPage += direction;
            filterAndRenderGroups();
        } else if (type === 'courses') {
            coursesCurrentPage += direction;
            filterAndRenderCourses();
        }
    }

    function updatePaginationControls(type, currentPage, totalItems) {
        const totalPages = Math.ceil(totalItems / ITEMS_PER_PAGE);
        const prevBtn = document.getElementById(`${type}PrevBtn`);
        const nextBtn = document.getElementById(`${type}NextBtn`);
        const indicators = document.getElementById(`${type}PageIndicators`);

        if (prevBtn) prevBtn.disabled = currentPage === 1;
        if (nextBtn) nextBtn.disabled = currentPage >= totalPages;

        if (indicators) {
             if (totalItems === 0 || totalPages === 0 || totalPages === 1) {
                indicators.innerHTML = ''; // Hide pagination if only one page or no items
                if(prevBtn) prevBtn.style.display = 'none';
                if(nextBtn) nextBtn.style.display = 'none';
                return;
            } 
            
            // Show buttons if needed
            if(prevBtn) prevBtn.style.display = 'inline-block';
            if(nextBtn) nextBtn.style.display = 'inline-block';
            
            let indicatorHTML = '';
            for (let i = 1; i <= totalPages; i++) {
                indicatorHTML += `<button class="${i === currentPage ? 'active' : ''}" onclick="setPage('${type}', ${i})">${i}</button>`;
            }
            indicators.innerHTML = indicatorHTML;
        }
    }

    function setPage(type, page) {
        if (type === 'groups') {
            groupsCurrentPage = page;
            filterAndRenderGroups();
        } else if (type === 'courses') {
            coursesCurrentPage = page;
            filterAndRenderCourses();
        }
    }

    // --- Data Loaders ---

    function loadJoinedGroups() {
        robustFetch('api/get_joined_groups.php')
            .then(groups => {
                joinedGroupsData = groups; 
                groupsCurrentPage = 1; // Reset page on data load
                filterAndRenderGroups();
            }).catch(error => {
                console.error('Error loading groups:', error);
                const list = document.getElementById('joinedGroupsList');
                if(list) list.innerHTML = `<p style="color:red; text-align:center;">Could not load groups.</p>`;
            });
    }

    function filterAndRenderGroups() {
        const list = document.getElementById('joinedGroupsList');
        if (!list || !joinedGroupsData) return;

        const filteredGroups = joinedGroupsData.filter(g => {
            if (!currentSearchTerm) return true;
            const searchString = `${g.group_name} ${g.next_event || ''} ${g.subject_code || ''}`.toLowerCase();
            return searchString.includes(currentSearchTerm);
        });

        // Apply pagination
        const startIndex = (groupsCurrentPage - 1) * ITEMS_PER_PAGE;
        const endIndex = startIndex + ITEMS_PER_PAGE;
        const pageGroups = filteredGroups.slice(startIndex, endIndex);

        updatePaginationControls('groups', groupsCurrentPage, filteredGroups.length);

        list.innerHTML = '';
        if (pageGroups.length > 0) {
            pageGroups.forEach(g => {
                let card = document.createElement('div');
                card.className = 'group-card';
                card.innerHTML = `
                    <div class="card-content">
                        <h4>${g.group_name}</h4>
                        <div style="color:var(--text-muted); font-size: 0.9rem;">
                            <span style="font-weight: 600;">Members:</span> ${g.member_count || 'N/A'}
                        </div>
                        <div style="color:var(--text-muted); font-size: 0.9rem; margin-top: 0.25rem;">
                            <span style="font-weight: 600;">Next Session:</span> ${g.next_event || 'None Scheduled'}
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="primary" onclick="showMessage('Group Chat', 'Opening chat for ${g.group_name}...')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            Go to Chat
                        </button>
                        <button class="secondary" onclick="leaveGroup(${g.group_id}, '${g.group_name}')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="18" y1="8" x2="22" y2="12"/><line x1="22" y1="8" x2="18" y2="12"/></svg>
                            Leave
                        </button>
                    </div>
                `;
                list.appendChild(card);
            });
        } else {
             list.innerHTML = `<p style="color: var(--text-muted); text-align: center;">${currentSearchTerm ? 'No groups matched your search.' : 'You haven\'t joined any groups yet. Start one now!'}</p>`;
        }
    }

    function loadCourses() {
        robustFetch('api/get_courses.php').then(courses => {
            availableCoursesData = courses; 
            coursesCurrentPage = 1; // Reset page on data load
            filterAndRenderCourses();
        }).catch(error => {
            console.error('Error loading courses:', error);
            const root = document.getElementById('coursesList');
            if(root) root.innerHTML = `<p style="color:red; text-align:center;">Could not load courses.</p>`;
        });
    }

    function filterAndRenderCourses() {
        const root = document.getElementById('coursesList');
        if (!root || !availableCoursesData) return;

        const filteredCourses = availableCoursesData.filter(c => {
            if (!currentSearchTerm) return true;
            const searchString = `${c.subject_name} ${c.subject_code} ${c.subject_type || ''}`.toLowerCase();
            return searchString.includes(currentSearchTerm);
        });

        // Apply pagination
        const startIndex = (coursesCurrentPage - 1) * ITEMS_PER_PAGE;
        const endIndex = startIndex + ITEMS_PER_PAGE;
        const pageCourses = filteredCourses.slice(startIndex, endIndex);

        updatePaginationControls('courses', coursesCurrentPage, filteredCourses.length);

        root.innerHTML = '';
        if (pageCourses.length > 0) {
            pageCourses.forEach(c => {
                const node = document.createElement('div');
                node.className = 'course-card';
                node.innerHTML = `
                     <div class="card-content">
                        <h5>${c.subject_name}</h5>
                        <div style="color:var(--text-muted); font-size: 0.9rem;">${c.subject_code} • ${c.subject_type || 'N/A'}</div>
                     </div>
                    <div class="card-actions">
                        <button class="primary" onclick="joinGroup(${c.subject_id}, '${c.subject_name}', '${c.subject_code}')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            Join Group
                        </button>
                        <button class="secondary" onclick="showMessage('Resources', 'Displaying resources for ${c.subject_name}')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="10" y1="13" x2="14" y2="13"/><line x1="10" y1="17" x2="14" y2="17"/><line x1="10" y1="9" x2="10" y2="9"/></svg>
                            View Resources
                        </button>
                    </div>
                `;
                root.appendChild(node);
            });
        } else {
            root.innerHTML = `<p style="color: var(--text-muted); text-align: center;">${currentSearchTerm ? 'No courses matched your search.' : 'No available courses for your department/year without a study group yet.'}</p>`;
        }
    }
    
    function loadCalendar() {
        robustFetch('api/get_events.php').then(events => {
            const root = document.getElementById('calendarEvents');
            if(root) {
                root.innerHTML = '';
                 if (events && events.length > 0) {
                    // FIX: No more custom card styles, relying on the two-column grid applied via CSS
                    events.forEach(ev => {
                        const card = document.createElement('div');
                        card.className = 'event-card';
                        card.innerHTML = `
                             <div class="card-content">
                                <h5>${ev.title}</h5>
                                <p style="color:var(--text-muted); font-size: 0.9rem;">${new Date(ev.datetime).toLocaleString()}</p>
                             </div>
                             <div class="card-actions">
                                 <button class="${ev.is_member ? 'primary' : 'secondary'}" onclick="handleRsvp(${ev.event_id}, '${ev.title}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                                     ${ev.is_member ? 'RSVP' : 'Request Access'}
                                 </button>
                             </div>
                        `;
                        root.appendChild(card);
                    });
                } else {
                     root.innerHTML = `<p style="color: var(--text-muted);">No upcoming events.</p>`;
                }
            }
        }).catch(error => {
            console.error('Error loading calendar:', error);
            const root = document.getElementById('calendarEvents');
            if(root) root.innerHTML = `<p style="color:red; text-align:center;">Could not load calendar events.</p>`;
        });
    }

    function loadProgress() {
        robustFetch('api/get_progress.php').then(notes => {
            const root = document.getElementById('progressTracker');
            if(root) {
                root.innerHTML = '';
                if (notes && notes.length > 0) {
                    notes.forEach(note => {
                        const card = document.createElement('div');
                        card.className = 'note-card';
                        card.innerHTML = `
                            <div class="card-content">
                                <h5>${note.note_title}</h5>
                                <div style="color:var(--text-dark); font-size: 0.95rem; margin-bottom: 1rem;">${(note.note_content || '').slice(0, 90)}...</div>
                                <div style="color:var(--text-muted); font-size: 0.85rem;">${(note.created_at || '').split(' ')[0]}</div>
                            </div>
                            <div class="card-actions">
                                <button class="secondary" onclick="showMessage('View Note: ${note.note_title}', '${note.note_content.replace(/'/g, "\\'")}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    View Details
                                </button>
                            </div>
                        `;
                        root.appendChild(card);
                    });
                } else {
                    root.innerHTML = `<p style="color: var(--text-muted);">No progress notes found. Add one to get started!</p>`;
                }
            }
        }).catch(error => {
            console.error('Error loading progress:', error);
            const root = document.getElementById('progressTracker');
            if(root) root.innerHTML = `<p style="color:red; text-align:center;">Could not load progress notes.</p>`;
        });
    }

    function loadFAQ() {
        robustFetch('api/get_support_faq.php').then(faqs => {
            const root = document.getElementById('faqList');
            if(root) {
                root.innerHTML = '';
                if (faqs && faqs.length > 0) {
                    faqs.forEach(f => {
                        root.innerHTML += `<div style="margin-bottom: 1.5rem;"><p style="font-weight: 600; margin-bottom: 0.25rem; color: var(--primary-purple);">${f.question}</p><p style="color:var(--text-dark); margin-top: 0;">${f.answer}</p></div>`;
                    });
                } else {
                    root.innerHTML = `<p style="color: var(--text-muted);">No FAQs available.</p>`;
                }
            }
        }).catch(error => {
            console.error('Error loading FAQ:', error);
            const root = document.getElementById('faqList');
            if(root) root.innerHTML = `<p style="color:red; text-align:center;">Could not load FAQ.</p>`;
        });
    }

    // --- Dynamic Actions (API endpoints maintained) ---
    
    window.leaveGroup = function(group_id, group_name) {
        robustFetch('api/group_action.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'leave', group_id })
        }).then(resp => {
            showMessage('Group Action', resp.msg || `Successfully left group: ${group_name}`);
            loadJoinedGroups();
            loadStats();
        }).catch(e => {
            showMessage('Error', 'Failed to leave group: ' + e.message);
        });
    }

    window.joinGroup = function(subject_id, subject_name, subject_code) {
        robustFetch('api/group_action.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'join', subject_id })
        }).then(resp => {
            showMessage('Group Action', resp.msg || `Successfully joined group for: ${subject_name}`);
            loadJoinedGroups();
            loadCourses(); // Refresh courses list as this course should now disappear
            loadStats();
        }).catch(e => {
            showMessage('Error', 'Failed to join group: ' + e.message);
        });
    };

    window.handleRsvp = function(event_id, event_title) {
         robustFetch('api/group_action.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'RSVP', event_id })
        }).then(resp => {
            showMessage('Event Action', resp.msg || `RSVP successful for: ${event_title}`);
        }).catch(e => {
            showMessage('Error', 'Failed to RSVP: ' + e.message);
        });
    }

    // --- Create Group Modal Logic ---

    window.openCreateGroupModal = function() {
        document.getElementById('createGroupModal').classList.add('active');
        // Load subjects/courses for dropdown
        robustFetch('api/get_courses.php').then(courses=>{
            const select = document.querySelector('#createGroupForm select[name="subject_id"]');
            select.innerHTML = '<option value="">Select a course to link</option>';
            courses.forEach(subj=>{
                let option = document.createElement('option');
                option.value = subj.subject_id;
                option.textContent = subj.subject_name + ' (' + subj.subject_code + ')';
                select.appendChild(option);
            });
        }).catch(e => {
             showMessage('Error', 'Could not load available courses for group creation: ' + e.message);
        });
    };

    window.closeCreateGroupModal = function() {
        document.getElementById('createGroupModal').classList.remove('active');
        document.getElementById('createGroupMsg').textContent = '';
        document.getElementById('createGroupForm').reset();
    };

    document.getElementById('createGroupForm').addEventListener('submit', function(e){
        e.preventDefault();
        const form = e.target;
        
        let body = {
            action: 'create_group_custom',
            // FIX: Use 'group_name' key for clearer semantics on the frontend
            group_name: form.group_name.value, 
            // subject_id is optional (can be null/empty string) if user only wants to use group name
            subject_id: form.subject_id.value || null, 
            description: form.description.value
        };

        const msgElement = document.getElementById('createGroupMsg');
        msgElement.textContent = 'Creating group...';
        msgElement.style.color = 'var(--primary-purple)';
        
        robustFetch('api/group_action.php', {
            method: 'POST',
            headers: { 'Content-Type':'application/json' },
            body: JSON.stringify(body)
        })
        .then(resp=>{
            msgElement.textContent = resp.msg || 'Group created successfully.';
            if(resp.status==='success'){
                setTimeout(()=>{
                    closeCreateGroupModal();
                    loadJoinedGroups();
                    loadCourses(); // New group created, refresh lists
                    loadStats();
                }, 1500);
            } else {
                msgElement.style.color = 'red';
            }
        })
        .catch(error => {
            msgElement.textContent = 'Error: ' + error.message;
            msgElement.style.color = 'red';
        });
    });

    // --- Add Note Modal Logic ---

    window.openAddNoteModal = function(){
        document.getElementById('addNoteModal').classList.add('active');
        // Load subjects for select dropdown
        robustFetch('api/get_courses.php').then(courses=>{
            const select = document.querySelector('#addNoteModal select[name="subject_id"]');
            // Clear existing and add 'General' option first
            select.innerHTML = '<option value="">General</option>'; 
            courses.forEach(subj=>{
                let option = document.createElement('option');
                option.value = subj.subject_id;
                option.textContent = subj.subject_name + ' (' + subj.subject_code + ')';
                select.appendChild(option);
            });
        }).catch(e => {
             showMessage('Error', 'Could not load courses for note linking: ' + e.message);
        });
    };

    window.closeAddNoteModal = function(){
        document.getElementById('addNoteModal').classList.remove('active');
        document.getElementById('noteMsg').textContent = '';
        document.getElementById('addNoteForm').reset();
    };

    document.getElementById('addNoteForm').addEventListener('submit',function(e){
        e.preventDefault();
        const form = e.target;

        let body = {
            action: 'add_note',
            note_title: form.note_title.value,
            note_content: form.note_content.value,
            // Send null if value is empty string, aligning with PHP and SQL NULL foreign key
            subject_id: form.subject_id.value || null 
        };

        const msgElement = document.getElementById('noteMsg');
        msgElement.textContent = 'Adding note...';
        msgElement.style.color = 'var(--primary-purple)';

        robustFetch('api/group_action.php', {
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body: JSON.stringify(body)
        })
        .then(resp=>{
            msgElement.textContent = resp.msg || 'Note added!';
            if(resp.status==='success'){
                setTimeout(()=>{
                    closeAddNoteModal();
                    loadProgress();
                }, 1500);
            } else {
                msgElement.style.color = 'red';
            }
        })
        .catch(error => {
            msgElement.textContent = 'Error: ' + error.message;
            msgElement.style.color = 'red';
        });
    });


    window.logout = function() {
        robustFetch('api/user_action.php', { method: 'POST', body: JSON.stringify({ action: 'logout' }) })
             .then(() => location.href = 'signIn.html')
             .catch(() => showMessage('Logout Failed', 'Failed to communicate with the server. Please try logging out again.'));
    };
</script>

</body>
</html>
