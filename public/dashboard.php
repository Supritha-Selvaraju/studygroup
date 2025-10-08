<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>Study Group Dashboard</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<style>
		:root{
			--bg:#0f1220;
			--panel:#12162a;
			--panel-2:#171c34;
			--text:#e9ecf5;
			--muted:#aab0c5;
			--brand:#5b8cff;
			--brand-2:#7aa2ff;
			--accent:#22c55e;
			--warning:#f59e0b;
			--danger:#ef4444;
			--line:#243053;
			--chip:#1e2544;
			--chip-2:#0e152e;
			--shadow: 0 8px 24px rgba(0,0,0,.3);
			--radius:12px;
		}
		*{box-sizing:border-box}
		html,body{height:100%}
		body{
			margin:0;
			font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
			background:linear-gradient(180deg,#0c0f1b 0%, #0f1220 100%);
			color:var(--text);
		}
		a{color:inherit;text-decoration:none}
		.container{
			display:grid;
			grid-template-columns: 280px 1fr;
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
			background:linear-gradient(180deg, #131938, #101630);
			border:1px solid var(--line);
			box-shadow:var(--shadow);
			padding:14px 16px;
			border-radius:16px;
			position:sticky;
			top:0;
			z-index:10;
			backdrop-filter: blur(6px);
		}
		.brand{
			display:flex;align-items:center;gap:12px;font-weight:700;letter-spacing:.2px
		}
		.brand-badge{
			width:36px;height:36px;border-radius:9px;
			background: radial-gradient(120% 120% at 20% 20%, #7aa2ff 0%, #5b8cff 35%, #6fe4b5 100%);
			display:grid;place-items:center;
			color:#0b1130;font-weight:900;
			box-shadow: 0 8px 24px rgba(91,140,255,.35), inset 0 0 12px rgba(255,255,255,.25);
		}
		.search{
			flex:1;max-width:520px;margin:0 16px;position:relative
		}
		.search input{
			width:100%;padding:12px 40px 12px 40px;border-radius:12px;border:1px solid var(--line);
			background:linear-gradient(180deg, #0c1126, #0a1022);
			color:var(--text);outline:none;
		}
		.search .icon{
			position:absolute;left:12px;top:10px;color:var(--muted);font-size:18px
		}
		.user{
			display:flex;align-items:center;gap:12px
		}
		.avatar{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#334,#667)}
		.badge{
			font-size:12px;padding:4px 8px;border-radius:999px;background:var(--chip);border:1px solid var(--line);color:var(--muted)
		}

		/* Sidebar */
		.sidebar{
			background:linear-gradient(180deg, #101633, #0e1530);
			border:1px solid var(--line);
			border-radius:16px;
			padding:16px;
			box-shadow:var(--shadow);
			position:sticky;
			top:84px;
			height:fit-content;
		}
		.section-title{
			font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.12em;margin:12px 0 8px
		}
		.filter{
			background:var(--panel);
			border:1px solid var(--line);
			border-radius:12px;
			padding:12px;
			margin-bottom:12px;
		}
		label{font-size:12px;color:var(--muted);display:block;margin-bottom:6px}
		select, input[type="text"], input[type="date"]{
			width:100%;
			padding:10px 12px;border-radius:10px;border:1px solid var(--line);
			background:linear-gradient(180deg,#0b1026,#0b1122);
			color:var(--text);outline:none;
		}
		.chips{display:flex;flex-wrap:wrap;gap:8px}
		.chip{
			padding:6px 10px;border-radius:999px;background:var(--chip);border:1px solid var(--line);color:var(--muted);font-size:12px
		}
		.btn{
			display:inline-flex;align-items:center;gap:8px;
			padding:10px 12px;border-radius:10px;border:1px solid var(--line);
			background:linear-gradient(180deg,#0b1434, #0b1330);
			color:var(--text);cursor:pointer
		}
		.btn.primary{background:linear-gradient(180deg, var(--brand), var(--brand-2)); color:#0a0f25; font-weight:700;border:none}
		.btn.block{width:100%;justify-content:center}
		hr.div{border:none;border-top:1px dashed var(--line);margin:12px 0}

		/* Main */
		.main{display:grid;gap:16px}
		.kpis{
			display:grid;grid-template-columns:repeat(4,1fr);gap:16px
		}
		.card{
			background:linear-gradient(180deg, #101633, #0f1430 55%, #0d1129);
			border:1px solid var(--line);
			border-radius:16px;padding:16px;
			box-shadow:var(--shadow);
		}
		.kpi{
			display:flex;align-items:center;gap:12px;
		}
		.kpi .num{font-size:28px;font-weight:800}
		.kpi .sub{color:var(--muted);font-size:12px}
		.kpi .pill{margin-left:auto;font-size:12px;padding:4px 8px;border-radius:999px;background:var(--chip);border:1px solid var(--line);color:var(--muted)}
		.kpi .icon{
			width:38px;height:38px;border-radius:10px;display:grid;place-items:center;
			background:linear-gradient(180deg,#122,#233);border:1px solid var(--line)
		}

		.grid-2{
			display:grid;grid-template-columns: 1.1fr .9fr;gap:16px
		}

		/* Create Group (CSS-only toggle via details) */
		details.create{
			background:linear-gradient(180deg, #0f1533, #0e142f);
			border:1px solid var(--line);border-radius:16px;padding:0;overflow:hidden
		}
		details.create summary{
			list-style:none;cursor:pointer;padding:16px 16px;display:flex;align-items:center;gap:12px;
			background:linear-gradient(180deg, rgba(91,140,255,.12), rgba(91,140,255,.05));
			border-bottom:1px solid var(--line);
		}
		details.create[open] summary{border-bottom:1px dashed var(--line)}
		details.create summary::-webkit-details-marker{display:none}
		.form{
			padding:16px;display:grid;grid-template-columns: repeat(2,1fr);gap:12px
		}
		.form .full{grid-column:1/-1}
		textarea{
			width:100%;min-height:90px;resize:vertical;
			padding:10px 12px;border-radius:10px;border:1px solid var(--line);
			background:linear-gradient(180deg,#0b1026,#0b1122);
			color:var(--text);outline:none;
		}
		.form .actions{
			display:flex;gap:10px;justify-content:flex-end
		}

		/* Groups table */
		.table{
			border:1px solid var(--line);border-radius:16px;overflow:hidden;background:var(--panel)
		}
		.table .head{
			display:grid;grid-template-columns: 1.2fr 1fr .8fr .8fr .8fr .6fr;gap:0;border-bottom:1px solid var(--line);
			background:linear-gradient(180deg, #101633, #0f1430);
			font-size:12px;color:var(--muted)
		}
		.row{
			display:grid;grid-template-columns: 1.2fr 1fr .8fr .8fr .8fr .6fr;gap:0;border-bottom:1px dashed var(--line);align-items:center
		}
		.cell{padding:12px 14px}
		.status{
			padding:6px 10px;border-radius:999px;font-size:12px;display:inline-flex;align-items:center;gap:8px;border:1px solid var(--line)
		}
		.status.active{background:rgba(34,197,94,.12);color:#a7f3d0;border-color:rgba(34,197,94,.3)}
		.status.full{background:rgba(245,158,11,.12);color:#fde68a;border-color:rgba(245,158,11,.3)}
		.status.closed{background:rgba(239,68,68,.12);color:#fecaca;border-color:rgba(239,68,68,.25)}
		.tag{padding:4px 8px;border-radius:999px;background:var(--chip);border:1px solid var(--line);font-size:12px;color:var(--muted);margin-right:6px}
		.table .foot{display:flex;justify-content:space-between;align-items:center;padding:10px 14px;color:var(--muted);font-size:12px}

		/* Announcements */
		.list{
			display:grid;gap:10px
		}
		.note{
			padding:12px;border:1px solid var(--line);border-radius:12px;
			background:linear-gradient(180deg,#0f1430,#0e142d);
		}
		.note b{color:#c6d0ff}
		.note .meta{color:var(--muted);font-size:12px}

		/* Calendar placeholder */
		.calendar{
			border:1px solid var(--line);border-radius:16px;background:var(--panel);padding:12px
		}
		.cal-grid{
			display:grid;grid-template-columns: repeat(7,1fr);gap:8px
		}
		.day{
			min-height:78px;border:1px solid var(--line);border-radius:10px;padding:8px;background:linear-gradient(180deg,#0d1230,#0b1127)
		}
		.day .d{font-size:12px;color:var(--muted)}
		.badge-mini{display:inline-block;margin-top:6px;padding:3px 6px;border-radius:999px;background:rgba(91,140,255,.15);border:1px solid rgba(91,140,255,.35);font-size:11px;color:#bcd1ff}

		/* Responsive */
		@media (max-width: 1100px){
			.kpis{grid-template-columns:repeat(2,1fr)}
			.grid-2{grid-template-columns:1fr}
			.container{grid-template-columns:1fr}
			.sidebar{position:static}
		}
		@media (max-width: 640px){
			.form{grid-template-columns:1fr}
			.table .head, .row{grid-template-columns: 1.2fr 1fr .8fr .8fr .8fr}
			.cell.hide-sm{display:none}
		}
	</style>
</head>
<body>
	<div class="container">
		<header>
			<div class="brand">
				<div class="brand-badge">SG</div>
				<div>
					<div style="font-size:14px;color:var(--muted);font-weight:600">Professional</div>
					<div style="font-size:18px">Study Group Dashboard</div>
				</div>
			</div>
			<div class="search">
				<span class="icon">🔎</span>
				<input type="text" placeholder="Search groups, courses, or members…"/>
			</div>
			<div class="user">
				<span class="badge">Student</span>
				<div class="avatar"></div>
			</div>
		</header>

		<!-- Sidebar Filters -->
		<aside class="sidebar">
			<div class="section-title">Quick actions</div>
			<button class="btn primary block">➕ Create New Group</button>

			<hr class="div">

			<div class="section-title">Filters</div>
			<div class="filter">
				<label>Department</label>
				<select>
					<option>All Departments</option>
					<option>CSE</option>
					<option>ECE</option>
					<option>EEE</option>
					<option>MECH</option>
					<option>CIVIL</option>
					<option>IT</option>
				</select>
			</div>
			<div class="filter">
				<label>Course</label>
				<select>
					<option>All Courses</option>
					<option>Data Structures</option>
					<option>Operating Systems</option>
					<option>DBMS</option>
					<option>Signals and Systems</option>
					<option>Thermodynamics</option>
				</select>
			</div>
			<div class="filter">
				<label>Semester</label>
				<select>
					<option>All Semesters</option>
					<option>Semester 1</option>
					<option>Semester 2</option>
					<option>Semester 3</option>
					<option>Semester 4</option>
					<option>Semester 5</option>
					<option>Semester 6</option>
					<option>Semester 7</option>
					<option>Semester 8</option>
				</select>
			</div>
			<div class="filter">
				<label>Curricular Type</label>
				<select>
					<option>All Types</option>
					<option>Core</option>
					<option>Elective</option>
					<option>Lab</option>
					<option>Project</option>
					<option>Club</option>
				</select>
			</div>
			<div class="filter">
				<label>Tags</label>
				<div class="chips">
					<span class="chip">Exam Prep</span>
					<span class="chip">Hackathon</span>
					<span class="chip">Research</span>
					<span class="chip">Peer Tutoring</span>
					<span class="chip">Workshops</span>
				</div>
			</div>
			<div class="filter">
				<label>Date Range</label>
				<input type="date"/>
			</div>
			<button class="btn block">Reset Filters</button>
		</aside>

		<main class="main">
			<!-- KPI Cards -->
			<div class="kpis">
				<div class="card kpi">
					<div class="icon">👥</div>
					<div>
						<div class="num">24</div>
						<div class="sub">Active Groups</div>
					</div>
					<div class="pill">+3 this week</div>
				</div>
				<div class="card kpi">
					<div class="icon">📚</div>
					<div>
						<div class="num">68</div>
						<div class="sub">Courses Covered</div>
					</div>
					<div class="pill">Core + Electives</div>
				</div>
				<div class="card kpi">
					<div class="icon">🕒</div>
					<div>
						<div class="num">142</div>
						<div class="sub">Upcoming Sessions</div>
					</div>
					<div class="pill">Next 30 days</div>
				</div>
				<div class="card kpi">
					<div class="icon">⭐</div>
					<div>
						<div class="num">4.7</div>
						<div class="sub">Avg. Feedback</div>
					</div>
					<div class="pill">Quality</div>
				</div>
			</div>

			<div class="grid-2">
				<!-- Create Group (CSS-only expandable) -->
				<details class="create card">
					<summary>
						<span class="btn">🧩 Group Builder</span>
						<span style="color:var(--muted);margin-left:8px">Create a new study group with course and semester details</span>
					</summary>
					<form class="form">
						<div>
							<label>Group Name</label>
							<input type="text" placeholder="e.g., DS Exam Sprint S5"/>
						</div>
						<div>
							<label>Department</label>
							<select>
								<option>CSE</option><option>ECE</option><option>EEE</option><option>MECH</option><option>CIVIL</option><option>IT</option>
							</select>
						</div>
						<div>
							<label>Course</label>
							<select>
								<option>Data Structures</option><option>Operating Systems</option><option>DBMS</option><option>Computer Networks</option>
							</select>
						</div>
						<div>
							<label>Semester</label>
							<select>
								<option>Semester 1</option><option>Semester 2</option><option>Semester 3</option><option selected>Semester 5</option><option>Semester 6</option>
							</select>
						</div>
						<div>
							<label>Curricular Type</label>
							<select>
								<option>Core</option><option>Elective</option><option>Lab</option><option>Project</option><option>Club</option>
							</select>
						</div>
						<div>
							<label>Max Members</label>
							<select>
								<option>5</option><option>10</option><option selected>15</option><option>20</option>
							</select>
						</div>
						<div class="full">
							<label>Description</label>
							<textarea placeholder="Goals, meeting cadence, and resources…"></textarea>
						</div>
						<div class="full">
							<label>Tags</label>
							<div class="chips">
								<span class="chip">Exam Prep</span>
								<span class="chip">Interview</span>
								<span class="chip">Projects</span>
								<span class="chip">Peer Tutoring</span>
								<span class="chip">Research</span>
							</div>
						</div>
						<div class="full actions">
							<button type="reset" class="btn">Clear</button>
							<button type="button" class="btn">Save as Draft</button>
							<button type="submit" class="btn primary">Create Group</button>
						</div>
					</form>
				</details>

				<!-- Announcements -->
				<div class="card">
					<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
						<div class="section-title" style="margin:0">Announcements</div>
						<span class="badge">Last updated: Today</span>
					</div>
					<div class="list">
						<div class="note">
							<b>Hackathon Prep Series</b>
							<div class="meta">New 3-week series for DSA and System Design. Starts Oct 15.</div>
						</div>
						<div class="note">
							<b>Lab Peer Tutoring</b>
							<div class="meta">CSE labs S3 and S5 get new tutoring slots on Wed and Fri.</div>
						</div>
						<div class="note">
							<b>Research Circles</b>
							<div class="meta">AI in Education and Embedded Systems groups open for signups.</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Groups Directory -->
			<div class="card">
				<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
					<div class="section-title" style="margin:0">Groups Directory</div>
					<div style="display:flex;gap:8px">
						<span class="badge">Sort: Popular</span>
						<span class="badge">View: Table</span>
					</div>
				</div>

				<div class="table">
					<div class="head">
						<div class="cell">Group</div>
						<div class="cell">Course</div>
						<div class="cell">Dept</div>
						<div class="cell">Semester</div>
						<div class="cell">Tags</div>
						<div class="cell hide-sm">Status</div>
					</div>

					<div class="row">
						<div class="cell"><b>DS Exam Sprint S5</b><br><span class="meta" style="color:var(--muted);font-size:12px">Lead: Arjun • 12/15</span></div>
						<div class="cell">Data Structures</div>
						<div class="cell">CSE</div>
						<div class="cell">S5</div>
						<div class="cell">
							<span class="tag">Exam Prep</span><span class="tag">Peer Tutoring</span>
						</div>
						<div class="cell hide-sm"><span class="status active">● Open</span></div>
					</div>

					<div class="row">
						<div class="cell"><b>Signals Mastery</b><br><span class="meta" style="color:var(--muted);font-size:12px">Lead: Lekha • 10/10</span></div>
						<div class="cell">Signals and Systems</div>
						<div class="cell">ECE</div>
						<div class="cell">S4</div>
						<div class="cell">
							<span class="tag">Core</span><span class="tag">Workshops</span>
						</div>
						<div class="cell hide-sm"><span class="status full">● Full</span></div>
					</div>

					<div class="row">
						<div class="cell"><b>DBMS Case Studies</b><br><span class="meta" style="color:var(--muted);font-size:12px">Lead: Priya • 7/15</span></div>
						<div class="cell">DBMS</div>
						<div class="cell">IT</div>
						<div class="cell">S5</div>
						<div class="cell">
							<span class="tag">Project</span><span class="tag">Research</span>
						</div>
						<div class="cell hide-sm"><span class="status active">● Open</span></div>
					</div>

					<div class="row">
						<div class="cell"><b>Thermo Lab Club</b><br><span class="meta" style="color:var(--muted);font-size:12px">Lead: Mohan • 9/12</span></div>
						<div class="cell">Thermodynamics</div>
						<div class="cell">MECH</div>
						<div class="cell">S3</div>
						<div class="cell">
							<span class="tag">Lab</span><span class="tag">Hands-on</span>
						</div>
						<div class="cell hide-sm"><span class="status closed">● Closed</span></div>
					</div>

					<div class="foot">
						<span>Showing 1–4 of 24 groups</span>
						<div>
							<span class="badge">Prev</span>
							<span class="badge">1</span>
							<span class="badge">2</span>
							<span class="badge">3</span>
							<span class="badge">Next</span>
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
					<div class="cal-grid">
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
</body>
</html>