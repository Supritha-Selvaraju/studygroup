<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: signin.html");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1"/>
	<title>Study Group Dashboard — Interactive</title>

	<!-- Fonts (same placeholders kept) -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

	<style>
		:root{
  /* Updated theme colors */
  --bg:#f9f9f9;              /* Light primary background */
  --panel:#ffffff;           /* Panels and cards white */
  --panel-2:#f3f3f3;         /* Slightly off-white secondary panels */
  --text:#1e1e1e;            /* Dark text */
  --muted:#6b7280;           /* Muted gray text */
  --brand:#4e1a73;           /* Accent purple */
  --brand-2:#8a4fd4;         /* Slightly lighter gradient purple */
  --accent:#22c55e;          /* Accent green (unchanged) */
  --line:#e2e2e2;            /* Light gray borders */
  --chip:#f2e7ff;            /* Soft lavender chip */
  --shadow:0 8px 24px rgba(0,0,0,.1);
  --radius:12px;
}

*{box-sizing:border-box}
html,body{height:100%}

body{
  margin:0;
  font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
  background:var(--bg);
  color:var(--text);
}

a{color:inherit;text-decoration:none}

.container{
  display:grid;
  grid-template-columns:280px 1fr;
  gap:20px;
  padding:20px;
  max-width:1400px;
  margin:0 auto;
}

header{
  grid-column:1/-1;
  display:flex;
  align-items:center;
  justify-content:space-between;
  background:linear-gradient(180deg,#ffffff 0%, #f9f9f9 100%);
  border:1px solid var(--line);
  box-shadow:var(--shadow);
  padding:14px 16px;
  border-radius:16px;
  position:sticky;
  top:0;
  z-index:10;
  backdrop-filter:blur(6px);
}

.brand{
  display:flex;
  align-items:center;
  gap:12px;
  font-weight:700;
  letter-spacing:.2px;
}

.brand-badge{
  width:36px;
  height:36px;
  border-radius:9px;
  display:grid;
  place-items:center;
  color:#fff;
  font-weight:900;
  background:radial-gradient(120% 120% at 20% 20%,#8a4fd4 0%,#6f2da8 35%,#22c55e 100%);
  box-shadow:0 8px 24px rgba(111,45,168,.35), inset 0 0 12px rgba(255,255,255,.25);
}

.search{
  flex:1;
  max-width:520px;
  margin:0 16px;
  position:relative;
  display:flex;
  align-items:center;
  gap:12px;
}

.search input{
  width:100%;
  padding:8px 10px 8px 35px
  border-radius-left:20px;
  border:5px solid var(--line);
  background:#f3f3f3;
  color:var(--text);
  outline:none;
}

.search .icon{
  position:absolute;
  left:1px;
  top:25px;
  color:var(--muted);
  font-size:15px;
}


.user{
  display:flex;
  align-items:center;
  gap:12px
}

.avatar{
  width:36px;
  height:36px;
  border-radius:50%;
  background:linear-gradient(135deg,#c9a9e6,#e2ccf5);
}

.badge{
  font-size:12px;
  padding:4px 8px;
  border-radius:999px;
  background:var(--chip);
  border:1px solid var(--line);
  color:var(--muted);
}

/* Sidebar */
.sidebar{
  background:var(--panel);
  border:1px solid var(--line);
  border-radius:16px;
  padding:16px;
  box-shadow:var(--shadow);
  position:sticky;
  top:84px;
  height:fit-content;
}

.section-title{
  font-size:12px;
  color:var(--muted);
  text-transform:uppercase;
  letter-spacing:.12em;
  margin:12px 0 8px;
}

.filter{
  background:var(--panel-2);
  border:1px solid var(--line);
  border-radius:12px;
  padding:12px;
  margin-bottom:12px;
}

label{
  font-size:12px;
  color:var(--muted);
  display:block;
  margin-bottom:6px;
}

select, input[type="text"], input[type="date"]{
  width:100%;
  padding:10px 12px;
  border-radius:10px;
  border:1px solid var(--line);
  background:#f9f9f9;
  color:var(--text);
  outline:none;
}

.chips{
  display:flex;
  flex-wrap:wrap;
  gap:8px;
}

.chip{
  padding:6px 10px;
  border-radius:999px;
  background:var(--chip);
  border:1px solid var(--line);
  color:var(--muted);
  font-size:12px;
}


.btn{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:10px 12px;
  border-radius:10px;
  border:1px solid var(--line);
  background:#f3f3f3;
  color:var(--text);
  cursor:pointer;
}

.btn.primary{
  background:linear-gradient(180deg,var(--brand),var(--brand-2));
  color:#ffffff;
  font-weight:700;
  border:none;
}

.btn.block{width:100%;justify-content:center}

hr.div{border:none;border-top:1px dashed var(--line);margin:12px 0}

/* Main */
.main{display:grid;gap:16px}
.kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}

.card{
  background:var(--panel);
  border:1px solid var(--line);
  border-radius:16px;
  padding:16px;
  box-shadow:var(--shadow);
}

.kpi{display:flex;align-items:center;gap:12px}
.kpi .num{font-size:28px;font-weight:800}
.kpi .sub{color:var(--muted);font-size:12px}
.kpi .pill{margin-left:auto;font-size:12px;padding:4px 8px;border-radius:999px;background:var(--chip);border:1px solid var(--line);color:var(--muted)}
.kpi .icon{width:38px;height:38px;border-radius:10px;display:grid;place-items:center;background:#f5f0fa;border:1px solid var(--line)}

.grid-2{display:grid;grid-template-columns:1.1fr .9fr;gap:16px}

/* Create Group */
details.create{
  background:var(--panel);
  border:1px solid var(--line);
  border-radius:16px;
  padding:0;
  overflow:hidden;
}

details.create summary{
  list-style:none;
  cursor:pointer;
  padding:16px;
  display:flex;
  align-items:center;
  gap:12px;
  background:rgba(111,45,168,.05);
  border-bottom:1px solid var(--line);
}

details.create[open] summary{border-bottom:1px dashed var(--line)}
details.create summary::-webkit-details-marker{display:none}

.form{padding:16px;display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
.form .full{grid-column:1/-1}

textarea{
  width:100%;
  min-height:90px;
  resize:vertical;
  padding:10px 12px;
  border-radius:10px;
  border:1px solid var(--line);
  background:#f9f9f9;
  color:var(--text);
  outline:none;
}

.form .actions{display:flex;gap:10px;justify-content:flex-end}

/* Table */
.table{
  border:1px solid var(--line);
  border-radius:16px;
  overflow:hidden;
  background:var(--panel);
}

.table .head{
  display:grid;
  grid-template-columns:1.2fr 1fr .8fr .8fr .8fr .6fr;
  gap:0;
  border-bottom:1px solid var(--line);
  background:#f3f3f3;
  font-size:12px;
  color:var(--muted);
}

.row{
  display:grid;
  grid-template-columns:1.2fr 1fr .8fr .8fr .8fr .6fr;
  gap:0;
  border-bottom:1px dashed var(--line);
  align-items:center;
}

.cell{padding:12px 14px}

.status{
  padding:6px 10px;
  border-radius:999px;
  font-size:12px;
  display:inline-flex;
  align-items:center;
  gap:8px;
  border:1px solid var(--line);
}

.status.active{background:rgba(34,197,94,.12);color:#166534;border-color:rgba(34,197,94,.3)}
.status.full{background:rgba(245,158,11,.12);color:#92400e;border-color:rgba(245,158,11,.3)}
.status.closed{background:rgba(239,68,68,.12);color:#991b1b;border-color:rgba(239,68,68,.25)}

.tag{
  padding:4px 8px;
  border-radius:999px;
  background:var(--chip);
  border:1px solid var(--line);
  font-size:12px;
  color:var(--muted);
  margin-right:6px;
}

.table .foot{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:10px 14px;
  color:var(--muted);
  font-size:12px;
}

/* Announcements */
.list{display:grid;gap:10px}
.note{
  padding:12px;
  border:1px solid var(--line);
  border-radius:12px;
  background:var(--panel-2);
}
.note b{color:var(--brand)}
.note .meta{color:var(--muted);font-size:12px}

/* Calendar */
.calendar{
  border:1px solid var(--line);
  border-radius:16px;
  background:var(--panel);
  padding:12px;
}

.cal-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:8px}

.day{
  min-height:78px;
  border:1px solid var(--line);
  border-radius:10px;
  padding:8px;
  background:var(--panel-2);
}

.day .d{font-size:12px;color:var(--muted)}
.badge-mini{
  display:inline-block;
  margin-top:6px;
  padding:3px 6px;
  border-radius:999px;
  background:rgba(111,45,168,.1);
  border:1px solid rgba(111,45,168,.2);
  font-size:11px;
  color:var(--brand);
}

/* Responsive */
@media (max-width:1100px){
  .kpis{grid-template-columns:repeat(2,1fr)}
  .grid-2{grid-template-columns:1fr}
  .container{grid-template-columns:1fr}
  .sidebar{position:static}
}
@media (max-width:640px){
  .form{grid-template-columns:1fr}
  .table .head, .row{grid-template-columns:1.2fr 1fr .8fr .8fr .8fr}
  .cell.hide-sm{display:none}
}

.small-muted{font-size:12px;color:var(--muted)}
.controls-row{display:flex;gap:8px;align-items:center}
.file-input{display:flex;gap:8px;align-items:center}


	</style>
</head>
<body>
	<div class="container">
		<header>
			<div class="brand">
				<div class="brand-badge">SG</div>
				<div>
					<div style="font-size:20px;color:var(--muted);font-weight:600">PROFESSIONAL</div>
					<div style="font-size:20px">STUDY BOARD  DASHBOARD</div>
				</div>
			</div>

			<div class="search">
				<span class="icon"> 🔎</span>
				<input id="searchInput" type="text" placeholder=  " Search groups,courses,dept"/>
				<div style="width:12px"></div>
				<div class="file-input">
					<label class="btn" for="excelFile">📄 Load Excel</label>
					<input id="excelFile" type="file" accept=".xlsx,.xls" style="display:none"/>
					<span id="fileName" class="small-muted">No file loaded</span>
				</div>
			</div>

			<div class="user">
				<span class="badge">Student</span>
				<div class="avatar"></div>
			</div>
		</header>

        <script>
    // Fetch departments from backend
    fetch("get_departments.php")
      .then(res => res.json())
      .then(data => {
        const deptSelect = document.getElementById("department");
        data.forEach(dept => {
          let option = document.createElement("option");
          option.value = dept.department_id;   // backend expects ID
          option.textContent = dept.name;      // user sees name
          deptSelect.appendChild(option);
        });
      })
      .catch(err => console.error("Error loading departments:", err));
  </script> 

        

		<!-- Sidebar Filters -->
		<aside class="sidebar">
			<div class="section-title">Quick actions</div>
			<button id="openCreateBtn" class="btn primary block">➕ Create New Group</button>

			<hr class="div">

			<div class="section-title">Filters</div>

			<div class="filter">
				<label>Department</label>
				<select id="filterDept">
					<option value="">All Departments</option>
				</select>
			</div>

			<div class="filter">
				<label>Course</label>
				<select id="filterCourse">
					<option value="">All Courses</option>
				</select>
			</div>

			<div class="filter">
				<label>Semester</label>
				<select id="filterSemester">
					<option value="">All Semesters</option>
				</select>
			</div>

			<div class="filter">
				<label>Year</label>
				<select id="filterYear">
					<option value="">All Years</option>
				</select>
			</div>

			<div class="filter">
				<label>Subject Type</label>
				<select id="filterSubjectType">
					<option value="">All Subject Types</option>
				</select>
			</div>

			<div class="filter">
				<label>Curricular Type</label>
				<select id="filterType">
					<option value="">All Types</option>
					<option>Core</option>
					<option>Elective</option>
					<option>Lab</option>
					<option>Project</option>
					<option>Club</option>
				</select>
			</div>

			<div class="filter">
				<label>Tags</label>
				<div class="chips" id="filterTags">
					<span data-tag="Exam Prep" class="chip">Exam Prep</span>
					<span data-tag="Hackathon" class="chip">Hackathon</span>
					<span data-tag="Research" class="chip">Research</span>
					<span data-tag="Peer Tutoring" class="chip">Peer Tutoring</span>
					<span data-tag="Workshops" class="chip">Workshops</span>
				</div>
			</div>

			<div class="filter">
				<label>Date Range</label>
				<input id="filterDate" type="date"/>
			</div>

			<button id="resetFiltersBtn" class="btn block">Reset Filters</button>
		</aside>

		<main class="main">
			<!-- KPIs -->
			<div class="kpis">
				<div class="card kpi">
					<div class="icon">👥</div>
					<div>
						<div id="kpiGroups" class="num">0</div>
						<div class="sub">Active Groups</div>
					</div>
					<div id="kpiGroupsPill" class="pill">+0 this week</div>
				</div>
				<div class="card kpi">
					<div class="icon">📚</div>
					<div>
						<div id="kpiCourses" class="num">0</div>
						<div class="sub">Courses Covered</div>
					</div>
					<div class="pill">Core + Electives</div>
				</div>
				<div class="card kpi">
					<div class="icon">🕒</div>
					<div>
						<div id="kpiSessions" class="num">0</div>
						<div class="sub">Upcoming Sessions</div>
					</div>
					<div class="pill">Next 30 days</div>
				</div>
				<div class="card kpi">
					<div class="icon">⭐</div>
					<div>
						<div id="kpiFeedback" class="num">4.7</div>
						<div class="sub">Avg. Feedback</div>
					</div>
					<div class="pill">Quality</div>
				</div>
			</div>

			<div class="grid-2">
				<!-- Create Group -->
				<details id="createDetails" class="create card">
					<summary>
						<span class="btn">🧩 Group Builder</span>
						<span id="createSummaryText" style="color:var(--muted);margin-left:8px">Create a new study group with course and semester details</span>
					</summary>

					<form id="createForm" class="form" onsubmit="return false;">
						<div>
							<label>Group Name</label>
							<input id="groupName" type="text" placeholder="e.g., DS Exam Sprint S5" required/>
						</div>

						<div>
							<label>Department</label>
							<select id="groupDept" required></select>
						</div>

						<div>
							<label>Course</label>
							<select id="groupCourse" required></select>
						</div>

						<div>
							<label>Course ID</label>
							<select id="groupCourseId"></select>
						</div>

						<div>
							<label>Semester</label>
							<select id="groupSemester" required></select>
						</div>

						<div>
							<label>Year</label>
							<select id="groupYear" required></select>
						</div>

						<div>
							<label>Subject Type</label>
							<select id="groupSubjectType"></select>
						</div>

						<div>
							<label>Curricular Type</label>
							<select id="groupType">
								<option>Core</option><option>Elective</option><option>Lab</option><option>Project</option><option>Club</option>
							</select>
						</div>

						<div>
							<label>Max Members</label>
							<select id="groupMax">
								<option>5</option><option>10</option><option selected>15</option><option>20</option>
							</select>
						</div>

						<div class="full">
							<label>Description</label>
							<textarea id="groupDesc" placeholder="Goals, meeting cadence, and resources…"></textarea>
						</div>

						<div class="full">
							<label>Tags (comma separated)</label>
							<input id="groupTags" type="text" placeholder="Exam Prep, Peer Tutoring"/>
						</div>

						<div class="full actions">
							<button id="clearBtn" type="reset" class="btn">Clear</button>
							<button id="saveDraftBtn" type="button" class="btn">Save as Draft</button>
							<button id="createBtn" type="submit" class="btn primary">Create Group</button>
						</div>
					</form>
				</details>

				<!-- Announcements -->
				<!-- Announcements -->
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
        <div class="section-title" style="margin:0">Announcements</div>
        <span id="annBadge" class="badge">Last updated: Today</span>
    </div>
    <div class="list" id="annList">
    <div class="list" id="annList">
    <div class="note">
        <b>Hackathon Prep Series</b>
        <div class="meta">Kickstart your hackathon journey with hands-on sessions on idea building, teamwork, and rapid prototyping starting  on October 15.</div>
    </div>
    <div class="note">
        <b>Lab Peer Tutoring</b>
        <div class="meta">Dedicated peer tutoring sessions for CSE S3 and S5 labs are available every Wednesday and Friday.</div>
    </div>
    <div class="note">
        <b>Research Circles</b>
        <div class="meta">Join our collaborative research circles focusing on AI in Education and Embedded Systems innovations.</div>
    </div>
    <div class="note">
        <b>Upcoming Workshops</b>
        <div class="meta">Enhance your technical skills through practical workshops on Python and Cloud Computing — register by October 12.</div>
    </div>
    <div class="note">
        <b>Peer Mentoring Program</b>
        <div class="meta">Participate in our mentoring program where seniors guide juniors on projects, learning strategies, and career growth.</div>
    </div>
</div>

</div>

</div>

			</div>

			<!-- Groups Directory -->
			<div class="card">
				<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
					<div class="section-title" style="margin:0">Groups Directory</div>
					<div style="display:flex;gap:8px;align-items:center">
						<span class="badge">Sort: Popular</span>
						<span style="width:12px"></span>
						<span class="badge">View: Table</span>
					</div>
				</div>

				<div class="table" id="groupsTableWrap">
					<div class="head">
						<div class="cell">Group</div>
						<div class="cell">Course</div>
						<div class="cell">Dept</div>
						<div class="cell">Semester</div>
						<div class="cell">Tags</div>
						<div class="cell hide-sm">Status</div>
					</div>
					<div id="groupsBody"></div>
					<div class="foot">
						<span id="tableInfo">Showing 0 of 0 groups</span>
						<div id="paginationControls">
							<button id="prevPage" class="badge">Prev</button>
							<span id="pageNumbers"></span>
							<button id="nextPage" class="badge">Next</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Calendar -->
			<div class="card">
				<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
					<div class="section-title" style="margin:0">Upcoming Sessions</div>
					<div class="badge">Week View</div>
				</div>
				<div class="calendar">
					<div class="cal-grid" id="calendarGrid">
						<div class="day"><div class="d">Mon</div><span class="badge-mini">DS Sprint 5–6 PM</span></div>
						<div class="day"><div class="d">Tue</div></div>
						<div class="day"><div class="d">Wed</div><span class="badge-mini">DBMS Case 4–5 PM</span></div>
						<div class="day"><div class="d">Thu</div></div>
						<div class="day"><div class="d">Fri</div><span class="badge-mini">Signals WS 3–4 PM</span></div>
						<div class="day"><div class="d">Sat</div></div>
						<div class="day"><div class="d">Sun</div></div>
					</div>
				</div>
			</div>
		</main>
	</div>

	<!-- SheetJS for Excel parsing -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

	<script>
/* ===== Sample fallback data (overridden when Excel is loaded) ===== */
const sampleData = [
	{"Department Name":"Aeronautical Engineering","Year":1,"Semester":"1","Course ID":"HS19151","Course Title":"Technical English","Subject Type":"Core"},
	{"Department Name":"Aeronautical Engineering","Year":1,"Semester":"1","Course ID":"MA19151","Course Title":"Algebra and Calculus","Subject Type":"Core"},
	{"Department Name":"Aeronautical Engineering","Year":1,"Semester":"1","Course ID":"PH19141","Course Title":"Physics of Materials","Subject Type":"Core"},
	{"Department Name":"Aeronautical Engineering","Year":1,"Semester":"1","Course ID":"GE19101","Course Title":"Engineering Graphics","Subject Type":"Core"},
	{"Department Name":"Aeronautical Engineering","Year":1,"Semester":"1","Course ID":"GE19121","Course Title":"Engineering Practices - Civil and Mechanical","Subject Type":"Core"},
	{"Department Name":"Aeronautical Engineering","Year":1,"Semester":"1","Course ID":"MC19101","Course Title":"Environmental Science and Engineering","Subject Type":"Non-Core"},
	{"Department Name":"Aeronautical Engineering","Year":1,"Semester":"2","Course ID":"MA19251","Course Title":"Differential Equations and Vector Calculus","Subject Type":"Core"},
	{"Department Name":"Aeronautical Engineering","Year":1,"Semester":"2","Course ID":"CY19241","Course Title":"Engineering Chemistry","Subject Type":"Core"},
	{"Department Name":"Aeronautical Engineering","Year":1,"Semester":"2","Course ID":"EE19242","Course Title":"Basic Electrical and Electronics Engineering","Subject Type":"Core"},
	{"Department Name":"Aeronautical Engineering","Year":1,"Semester":"2","Course ID":"GE19201","Course Title":"Engineering Mechanics","Subject Type":"Core"}
];

/* ===== State ===== */
let catalog = sampleData.slice();                // Excel rows
let groups = JSON.parse(localStorage.getItem('sg_groups') || '[]');
let drafts = JSON.parse(localStorage.getItem('sg_drafts') || '[]');
let currentPage = 1;
const pageSize = 6;

/* ===== DOM refs ===== */
const excelFile = document.getElementById('excelFile');
const fileNameSpan = document.getElementById('fileName');

const filterDept = document.getElementById('filterDept');
const filterCourse = document.getElementById('filterCourse');
const filterSemester = document.getElementById('filterSemester');
const filterType = document.getElementById('filterType');
const filterDate = document.getElementById('filterDate');
const filterYear = document.getElementById('filterYear');               // NEW
const filterSubjectType = document.getElementById('filterSubjectType'); // NEW

const resetFiltersBtn = document.getElementById('resetFiltersBtn');
const searchInput = document.getElementById('searchInput');

const groupDept = document.getElementById('groupDept');
const groupCourse = document.getElementById('groupCourse');
const groupCourseId = document.getElementById('groupCourseId');         // OPTIONAL
const groupSemester = document.getElementById('groupSemester');
const groupYear = document.getElementById('groupYear');                 // NEW
const groupSubjectType = document.getElementById('groupSubjectType');   // NEW
const groupName = document.getElementById('groupName');
const groupType = document.getElementById('groupType');
const groupMax = document.getElementById('groupMax');
const groupDesc = document.getElementById('groupDesc');
const groupTags = document.getElementById('groupTags');

const createBtn = document.getElementById('createBtn');
const saveDraftBtn = document.getElementById('saveDraftBtn');
const clearBtn = document.getElementById('clearBtn');
const createDetails = document.getElementById('createDetails');
const openCreateBtn = document.getElementById('openCreateBtn');

const groupsBody = document.getElementById('groupsBody');
const tableInfo = document.getElementById('tableInfo');
const prevPage = document.getElementById('prevPage');
const nextPage = document.getElementById('nextPage');
const pageNumbers = document.getElementById('pageNumbers');

const kpiGroups = document.getElementById('kpiGroups');
const kpiCourses = document.getElementById('kpiCourses');
const kpiSessions = document.getElementById('kpiSessions');
const kpiFeedback = document.getElementById('kpiFeedback');

/* ===== Utils ===== */
function unique(arr){ return [...new Set(arr)]; }
function fillSelect(selectEl, arr){
	selectEl.innerHTML = '';
	arr.forEach(v=>{
		const opt = document.createElement('option');
		if (v === '') { opt.value = ''; opt.textContent = (selectEl === groupCourse) ? 'Select..' : (selectEl.options[0]?.textContent || ''); }
		else { opt.value = v; opt.textContent = v; }
		selectEl.appendChild(opt);
	});
}
function escapeHtml(s){ if (!s && s!==0) return ''; return String(s).replace(/[&<>\"']/g, m=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[m])); }

/* ===== Populate dropdowns from catalog ===== */
function populateCatalogDropdowns(){
	// Departments
	const depts = unique(catalog.map(r => r['Department Name']).filter(Boolean));
	fillSelect(filterDept, ['','All Departments'].concat(depts));
	fillSelect(groupDept, [''].concat(depts));

	// Courses (Title + ID)
	const courses = unique(catalog.map(r => `${r['Course Title']} (${r['Course ID']})`).filter(Boolean));
	fillSelect(filterCourse, ['','All Courses'].concat(courses));
	fillSelect(groupCourse, [''].concat(courses));

	// Course IDs (separate)
	if (groupCourseId){
		const ids = unique(catalog.map(r => r['Course ID']).filter(Boolean));
		fillSelect(groupCourseId, [''].concat(ids));
	}

	// Semesters
	const semesters = unique(catalog.map(r => String(r['Semester'])).filter(Boolean));
	fillSelect(filterSemester, ['','All Semesters'].concat(semesters));
	fillSelect(groupSemester, ['','Select..'].concat(semesters));

	// Years
	const years = unique(catalog.map(r => String(r['Year'])).filter(Boolean));
	fillSelect(filterYear, ['','All Years'].concat(years));
	fillSelect(groupYear, [''].concat(years));

	// Subject Types
	const subTypes = unique(catalog.map(r => r['Subject Type']).filter(Boolean));
	fillSelect(filterSubjectType, ['','All Subject Types'].concat(subTypes));
	fillSelect(groupSubjectType, [''].concat(subTypes));

	// KPI courses = unique Course IDs
	kpiCourses.innerText = unique(catalog.map(r => r['Course ID']).filter(Boolean)).length;
}

/* ===== Excel reading ===== */
function readExcelFile(file){
	const reader = new FileReader();
	reader.onload = e=>{
		const data = new Uint8Array(e.target.result);
		const workbook = XLSX.read(data, {type:'array'});
		const firstSheetName = workbook.SheetNames[0];
		const worksheet = workbook.Sheets[firstSheetName];
		const json = XLSX.utils.sheet_to_json(worksheet, {defval:''});
		if (json && json.length){
			const normalized = json.map(row => ({
				'Department Name': row['Department Name'] || row['Department'] || row['Dept'] || row['department name'] || row['department'] || row['Dept Name'] || '',
				'Year': row['Year'] || row['year'] || '',
				'Semester': String(row['Semester'] || row['semester'] || row['Sem'] || row['sem'] || ''),
				'Course ID': row['Course ID'] || row['CourseID'] || row['course id'] || row['Course Code'] || '',
				'Course Title': row['Course Title'] || row['CourseTitle'] || row['Title'] || row['course title'] || row['Course'] || '',
				'Subject Type': row['Subject Type'] || row['SubjectType'] || row['Type'] || row['subject type'] || ''
			}));
			catalog = normalized;
			populateCatalogDropdowns();
			fileNameSpan.innerText = file.name;
			renderGroups();
		}else{
			alert('No data found in the selected Excel sheet.');
		}
	};
	reader.readAsArrayBuffer(file);
}

/* ===== Group creation ===== */
function createGroupObject(fromForm=true){
	return {
		id: 'g_' + Date.now(),
		name: groupName.value.trim(),
		department: groupDept.value || '',
		course: groupCourse.value || '',
		courseId: groupCourseId ? (groupCourseId.value || '') : '',
		year: groupYear ? (groupYear.value || '') : '',
		semester: groupSemester.value || '',
		subjectType: groupSubjectType ? (groupSubjectType.value || '') : '',
		type: groupType.value || '',
		maxMembers: groupMax.value || '',
		description: groupDesc.value.trim(),   // FIXED
		tags: (groupTags.value || '').split(',').map(t=>t.trim()).filter(Boolean),
		status: 'Open',
		lead: 'TBD',
		createdAt: new Date().toISOString(),
		visibility: fromForm ? 'active' : 'draft'
	};
}
function addGroupToState(groupObj, save=true){
	groups.unshift(groupObj);
	if (save) localStorage.setItem('sg_groups', JSON.stringify(groups));
	renderGroups();
	updateKPIs();
}
function saveDraft(){
	const draft = createGroupObject(false);
	draft.visibility = 'draft';
	drafts.unshift(draft);
	localStorage.setItem('sg_drafts', JSON.stringify(drafts));
	alert('Draft saved locally.');
}

/* ===== Rendering list ===== */
function renderGroups(){
	const filters = {
		dept: filterDept.value,
		course: filterCourse.value,
		semester: filterSemester.value,
		year: filterYear.value,
		subjectType: filterSubjectType.value,
		type: filterType.value,
		search: searchInput.value.trim().toLowerCase(),
		date: filterDate.value
	};

	let rows = groups.slice();

	if (filters.dept) rows = rows.filter(r => r.department === filters.dept);
	if (filters.course) rows = rows.filter(r => r.course === filters.course);
	if (filters.semester) rows = rows.filter(r => String(r.semester) === String(filters.semester));
	if (filters.year) rows = rows.filter(r => String(r.year) === String(filters.year));
	if (filters.subjectType) rows = rows.filter(r => r.subjectType === filters.subjectType);
	if (filters.type) rows = rows.filter(r => r.type === filters.type);
	if (filters.date) rows = rows.filter(r => r.createdAt.slice(0,10) === filters.date);
	if (filters.search){
		rows = rows.filter(r=>{
			const hay = (r.name + ' ' + r.course + ' ' + r.courseId + ' ' + r.department + ' ' + (r.tags||[]).join(' ') + ' ' + (r.subjectType||'') + ' ' + (r.year||'')).toLowerCase();
			return hay.includes(filters.search);
		});
	}

	const total = rows.length;
	const pages = Math.max(1, Math.ceil(total / pageSize));
	if (currentPage > pages) currentPage = pages;
	const start = (currentPage - 1) * pageSize;
	const pageRows = rows.slice(start, start + pageSize);

	groupsBody.innerHTML = '';
	pageRows.forEach(g=>{
		const div = document.createElement('div');
		div.className = 'row';
		div.innerHTML = `
			<div class="cell"><b>${escapeHtml(g.name)}</b><br>
				<span class="meta" style="color:var(--muted);font-size:12px">
					Lead: ${escapeHtml(g.lead)} • ${g.maxMembers || '0'}
					${g.year ? ' • Year ' + escapeHtml(g.year) : ''}
					${g.subjectType ? ' • ' + escapeHtml(g.subjectType) : ''}
				</span>
			</div>
			<div class="cell">${escapeHtml(g.course)}</div>
			<div class="cell">${escapeHtml(g.department)}</div>
			<div class="cell">${escapeHtml(g.semester)}</div>
			<div class="cell">${(g.tags||[]).map(t=>`<span class="tag">${escapeHtml(t)}</span>`).join('')}</div>
			<div class="cell hide-sm"><span class="status ${g.status==='Open'?'active':g.status==='Full'?'full':'closed'}">● ${escapeHtml(g.status)}</span></div>
		`;
		groupsBody.appendChild(div);
	});

	tableInfo.innerText = total ? `Showing ${start+1}-${start + pageRows.length} of ${total} groups` : 'Showing 0 of 0 groups';
	renderPagination(pages);
}

function renderPagination(pages){
	pageNumbers.innerHTML = '';
	for (let i=1;i<=pages;i++){
		const sp = document.createElement('span');
		sp.style.margin = '0 6px';
		sp.style.cursor = 'pointer';
		sp.style.padding = '6px 8px';
		sp.style.borderRadius = '8px';
		sp.style.border = '1px solid var(--line)';
		if (i===currentPage){
			sp.style.background = 'linear-gradient(180deg,var(--brand),var(--brand-2))';
			sp.style.color = '#061025';
			sp.style.fontWeight = '700';
		}
		sp.innerText = i;
		sp.addEventListener('click', ()=>{ currentPage = i; renderGroups(); });
		pageNumbers.appendChild(sp);
	}
	prevPage.onclick = ()=>{ if (currentPage>1){ currentPage--; renderGroups(); } };
	nextPage.onclick = ()=>{ if (currentPage<pages){ currentPage++; renderGroups(); } };
}

/* ===== KPIs ===== */
function updateKPIs(){
	kpiGroups.innerText = groups.length;
	kpiCourses.innerText = unique(groups.map(g => g.course)).length || kpiCourses.innerText;
	const upcoming = groups.filter(g=>{
		const created = new Date(g.createdAt);
		const diffDays = (Date.now() - created.getTime()) / (1000*60*60*24);
		return diffDays <= 30;
	}).length;
	kpiSessions.innerText = upcoming;
	const avg = parseFloat(localStorage.getItem('sg_feedback_avg') || '4.7');
	kpiFeedback.innerText = avg.toFixed(1);
}

/* ===== Events ===== */
excelFile.addEventListener('change', (e)=>{
	const f = e.target.files[0];
	if (!f) return;
	readExcelFile(f);
});

document.getElementById('filterTags').querySelectorAll('.chip').forEach(ch=>{
	ch.addEventListener('click', ()=>{ ch.classList.toggle('active'); });
});

openCreateBtn.addEventListener('click', ()=>{
	createDetails.open = true;
	createDetails.scrollIntoView({behavior:'smooth', block:'center'});
});

/* Use form submit so button click and Enter both work */
document.getElementById('createForm').addEventListener('submit', (e)=>{
	e.preventDefault();
	if (!groupName.value.trim()){ alert('Please enter group name'); return; }
	if (!groupDept.value){ alert('Please select Department'); return; }
	if (!groupCourse.value){ alert('Please select Course'); return; }
	if (!groupSemester.value){ alert('Please select Semester'); return; }
	if (!groupYear.value){ alert('Please select Year'); return; }

	const obj = createGroupObject(true);
	addGroupToState(obj, true);

	e.target.reset();
	createDetails.open = false;
	alert('Group created successfully!');
});

saveDraftBtn.addEventListener('click', ()=>{
	saveDraft();
	document.getElementById('createForm').reset();
});

resetFiltersBtn.addEventListener('click', ()=>{
	filterDept.value=''; filterCourse.value=''; filterSemester.value='';
	filterType.value=''; filterDate.value=''; searchInput.value='';
	filterYear.value=''; filterSubjectType.value='';
	currentPage=1; renderGroups();
});

searchInput.addEventListener('input', ()=>{ currentPage=1; renderGroups(); });

[filterDept, filterCourse, filterSemester, filterType, filterDate, filterYear, filterSubjectType]
	.filter(Boolean)
	.forEach(el=>{ el.addEventListener('change', ()=>{ currentPage=1; renderGroups(); }); });

/* Department → narrow related selects in form */
groupDept.addEventListener('change', ()=>{
	const dept = groupDept.value;
	const rows = catalog.filter(r => r['Department Name'] === dept);

	const courseOptions = unique(rows.map(r => `${r['Course Title']} (${r['Course ID']})`).filter(Boolean));
	fillSelect(groupCourse, [''].concat(courseOptions));

	const ids = unique(rows.map(r => r['Course ID']).filter(Boolean));
	if (groupCourseId) fillSelect(groupCourseId, [''].concat(ids));

	const years = unique(rows.map(r => String(r['Year'])).filter(Boolean));
	fillSelect(groupYear, [''].concat(years));

	const sems = unique(rows.map(r => String(r['Semester'])).filter(Boolean));
	fillSelect(groupSemester, [''].concat(sems));

	const subTypes = unique(rows.map(r => r['Subject Type']).filter(Boolean));
	fillSelect(groupSubjectType, [''].concat(subTypes));
});

/* Initial boot */
populateCatalogDropdowns();
renderGroups();
updateKPIs();

/* Seed demo groups first time */
if (!localStorage.getItem('sg_seeded')){
	if (groups.length === 0){
		const demo = [
			{name:'DS Exam Sprint S5', department:'Aeronautical Engineering', course:'Technical English (HS19151)', courseId:'HS19151', year:'1', semester:'5', subjectType:'Core', type:'Core', maxMembers:15, description:'Mock sprint', tags:['Exam Prep','Peer Tutoring'], status:'Open', lead:'Arjun', createdAt:new Date().toISOString()},
			{name:'Signals Mastery', department:'Aeronautical Engineering', course:'Basic Electrical and Electronics Engineering (EE19242)', courseId:'EE19242', year:'1', semester:'4', subjectType:'Core', type:'Core', maxMembers:10, description:'Signals workshop', tags:['Core','Workshops'], status:'Full', lead:'Lekha', createdAt:new Date().toISOString()},
			{name:'DBMS Case Studies', department:'Aeronautical Engineering', course:'Engineering Chemistry (CY19241)', courseId:'CY19241', year:'1', semester:'5', subjectType:'Project', type:'Project', maxMembers:7, description:'Case discussions', tags:['Project','Research'], status:'Open', lead:'Priya', createdAt:new Date().toISOString()}
		];
		groups = demo.concat(groups);
		localStorage.setItem('sg_groups', JSON.stringify(groups));
	}
	localStorage.setItem('sg_seeded','1');
	renderGroups();
	updateKPIs();
}
	</script>
</body>
</html>
