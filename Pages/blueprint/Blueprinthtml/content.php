<?php
session_start();
require_once ("../../../Resources/Includes/connect.php");
$ouabbrev= $_SESSION['login_ouabbrev'];
$sqluser = "select NETWORK_USERNAME,OU_NAME,SYS_USER_ROLE,FNAME,LNAME from PermittedUsers Inner Join Hierarchy ON PermittedUsers.USER_OU_MEMBERSHIP = Hierarchy.OU_ABBREV where OU_ABBREV = '$ouabbrev'";
$resultuser = $mysqli->query($sqluser);
$rowsuser = $resultuser->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Academic BluePrint</title>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="base.min.css"/>
    <link rel="stylesheet" href="fancy.min.css"/>
    <link rel="stylesheet" href="main.css"/>
    <script src="compatibility.min.js"></script>
    <script src="theViewer.min.js"></script>
    <script>
        try{
            theViewer.defaultViewer = new theViewer.Viewer({});
        }catch(e){}
    </script>
    <title></title>
</head>
<body>
<div id="sidebar">
    <div id="outline">
    </div>
</div>
<div id="page-container">
    <div id="pf1" class="pf w0 h0" data-page-no="1">
        <div class="pc pc1 w0 h0">
            <div class="t m0 x0 h1 y0 ff1 fs0 fc0 sc0 ls0 ws0">Blueprint <span class="_ _0"></span>for</div>
            <div class="t m0 x1 h1 y1 ff1 fs0 fc0 sc0 ls0 ws0">Academic</div>
            <div class="t m0 x2 h1 y2 ff1 fs0 fc0 sc0 ls0 ws0">Excellence</div>
            <div class="t m0 x3 h2 y3 ff1 fs0 fc1 sc0 ls0 ws0">College of <span class="_ _0"></span>Nursing<span
                    class="ff2 fc0"> </span></div>
            <div class="t m0 x4 h2 y4 ff2 fs0 fc0 sc0 ls0 ws0">March 2017</div>
        </div>
        <div class="pi" data-data='{"ctm":[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}'></div>
    </div>
    <div id="pf2" class="pf w0 h0" data-page-no="2">
        <div class="pc pc2 w0 h0"><img class="bi x5 y5 w1 h3" alt="" src="bg2.png"/>
            <div class="t m0 x3 h4 y6 ff3 fs1 fc1 sc0 ls1 ws0">Executive Summary</div>
            <div class="t m0 x3 h5 y7 ff2 fs2 fc1 sc0 ls0 ws0">The College of Nursing has had another superb year in
                academic, <span class="_ _0"></span>research, and practice/service
            </div>
            <div class="t m0 x3 h5 y8 ff2 fs2 fc1 sc0 ls0 ws0">excellence. Our national prominence and reputation
                continues <span class="_ _0"></span>to soar, and we now have a #1 ranking by
            </div>
            <div class="t m0 x3 h5 y9 ff2 fs2 fc1 sc0 ls0 ws0">US News &amp; World Report and #29 NIH ranking among
                Colleges <span class="_ _0"></span>of Nursing in the US.
            </div>
            <div class="t m0 x3 h6 ya ff1 fs2 fc1 sc0 ls0 ws0">Highlighted Contributions to the Academic Dashboard <span
                    class="_ _0"></span>Targets:
            </div>
            <div class="t m0 x6 h5 yb ff2 fs2 fc1 sc0 ls0 ws0">High-quality program outcomes with NCLEX and NP
                certification<span class="_ _0"></span> exams higher than national and
            </div>
            <div class="t m0 x6 h5 yc ff2 fs2 fc1 sc0 ls0 ws0">state averages; high graduate employability and
                satisfaction <span class="_ _0"></span>from employers; Largest BSN
            </div>
            <div class="t m0 x6 h5 yd ff2 fs2 fc1 sc0 ls0 ws0">program in South Carolina with two rural campuses
                (Salkehatchie <span class="_ _0"></span>and Lancaster); initiated new
            </div>
            <div class="t m0 x6 h5 ye ff2 fs2 fc1 sc0 ls0 ws0">and affordable online undergraduate RN-BSN program to
                facilitate<span class="_ _0"></span> access for associate degree
            </div>
            <div class="t m0 x6 h5 yf ff2 fs2 fc1 sc0 ls0 ws0">registered nurses across the state; Have the 2nd highest
                <span class="_ _0"></span>6-year graduation rate (78.9%) across
            </div>
            <div class="t m0 x6 h5 y10 ff2 fs2 fc1 sc0 ls0 ws0">campus; Quadrupled graduate admissions in past 5 yrs
                (from 75 <span class="_ _0"></span>new admits in 2011 to 314 in
            </div>
            <div class="t m0 x6 h5 y11 ff2 fs2 fc1 sc0 ls0 ws0">2015). Grant $$ awards increased 5-fold from the
                previous <span class="_ _0"></span>year.
            </div>
            <div class="t m0 x3 h6 y12 ff1 fs2 fc1 sc0 ls0 ws0">Academic Dashboard Targets Needing Improvement</div>
            <div class="t m0 x7 h5 y13 ff4 fs2 fc1 sc0 ls0 ws0"><span class="_ _1"> </span><span class="ff2">Student-Faculty ratios and number/proportion of tenure <span
                        class="_ _0"></span>track/tenured faculty. Highlighted </span></div>
            <div class="t m0 x6 h5 y14 ff2 fs2 fc1 sc0 ls0 ws0">Contributions to the Key Performance Indicators:</div>
            <div class="t m0 x3 h6 y15 ff1 fs2 fc1 sc0 ls0 ws0">Nursing Education Programs of Excellence:</div>
            <div class="t m0 x7 h5 y16 ff4 fs2 fc1 sc0 ls0 ws0"><span class="_ _1"> </span><span class="ff2">Achieved #1 ranking (1/470) in online graduate nursing <span
                        class="_ _0"></span>programs by US News &amp; World Report; </span></div>
            <div class="t m0 x6 h5 y17 ff2 fs2 fc1 sc0 ls0 ws0">(ranked #3 in 2015, ranked #16 in 2014; no ranking in
                <span class="_ _0"></span>2013);
            </div>
            <div class="t m0 x7 h5 y18 ff4 fs2 fc1 sc0 ls0 ws0"><span class="_ _1"> </span><span class="ff2">Strategically initiating new UG and graduate programs <span
                        class="_ _0"></span>(Fall, 2015) based on assessment of </span></div>
            <div class="t m0 x6 h5 y19 ff2 fs2 fc1 sc0 ls0 ws0">market demands, partnerships, clinical training
                availability;
            </div>
            <div class="t m0 x7 h5 y1a ff4 fs2 fc1 sc0 ls0 ws0"><span class="_ _1"> </span><span class="ff2">Increased flexibility for student learner (e.g., 3 ½ <span
                        class="_ _0"></span>year BSN completion option, summer offerings, </span></div>
            <div class="t m0 x6 h5 y1b ff2 fs2 fc1 sc0 ls0 ws0">blended and online courses, 8-week course options);
            </div>
            <div class="t m0 x7 h5 y1c ff4 fs2 fc1 sc0 ls0 ws0"><span class="_ _1"> </span><span class="ff2">Partnering with SC technical colleges for 2+2/dual <span
                        class="_ _0"></span>enrollment for RN-BSN program. </span></div>
            <div class="t m0 x7 h5 y1d ff4 fs2 fc1 sc0 ls0 ws0"><span class="_ _1"> </span><span class="ff2">Research/Scholarship: </span>
            </div>
            <div class="t m0 x7 h5 y1e ff4 fs2 fc1 sc0 ls0 ws0"><span class="_ _1"> </span><span class="ff2">Achieved highest ranking in NIH funding to Colleges of <span
                        class="_ _0"></span>Nursing’ history: #29 in 2016, #34 in </span></div>
            <div class="t m0 x6 h5 y1f ff2 fs2 fc1 sc0 ls0 ws0">2015, #60 in 2014, no ranking in 2013;</div>
            <div class="t m0 x7 h5 y20 ff4 fs2 fc1 sc0 ls0 ws0"><span class="_ _1"> </span><span class="ff2">Research grant awards five times higher than previous <span
                        class="_ _0"></span>year (2015: $2,490,033); </span><span class="ff2"> No. and </span></div>
            <div class="t m0 x6 h5 y21 ff2 fs2 fc1 sc0 ls0 ws0">quality of peer review dissemination/scholarship
                continue <span class="_ _0"></span>to improve.
            </div>
            <div class="t m0 x3 h6 y22 ff1 fs2 fc1 sc0 ls0 ws0">Practice/Service:</div>
            <div class="t m0 x7 h5 y23 ff4 fs2 fc1 sc0 ls0 ws0"><span class="_ _1"> </span><span class="ff2">Children and Family Health Care Center designated as Medical <span
                        class="_ _0"></span>Home in the Carolina Medical </span></div>
            <div class="t m0 x6 h5 y24 ff2 fs2 fc1 sc0 ls0 ws0">Homes Network and is now part of Medicaid’s
                auto-assignment;
            </div>
            <div class="t m0 x7 h5 y25 ff4 fs2 fc1 sc0 ls0 ws0"><span class="_ _1"> </span><span class="ff2">Center for Nursing Leadership provides statewide: Leadership <span
                        class="_ _0"></span>training for executive nurses (Amy </span></div>
            <div class="t m0 x6 h5 y26 ff2 fs2 fc1 sc0 ls0 ws0">Cockcroft Leadership Program); Nursing Workforce
                Data;<span class="_ _0"></span> One Voice One Plan Initiative.
            </div>
            <div class="t m0 x3 h6 y27 ff1 fs2 fc1 sc0 ls0 ws0">Vibrant and Respectful Environment/Resourced <span
                    class="_ _0"></span>Infrastructure:
            </div>
            <div class="t m0 x7 h5 y28 ff4 fs2 fc1 sc0 ls0 ws0"><span class="_ _1"> </span><span class="ff2">77% of FT faculty are doctoral prepared (increase from <span
                        class="_ _0"></span>67% in 2012). Eight of 10 faculty </span></div>
            <div class="t m0 x6 h5 y29 ff2 fs2 fc1 sc0 ls0 ws0">members with terminal master’s degrees are enrolled
                <span class="_ _0"></span>in doctoral programs;
            </div>
            <div class="t m0 x7 h5 y2a ff4 fs2 fc1 sc0 ls0 ws0"><span class="_ _1"> </span><span class="ff2">Cultivating partnerships with health systems across South <span
                        class="_ _0"></span>Carolina, Georgia, and North Carolina </span></div>
            <div class="t m0 x6 h5 y2b ff2 fs2 fc1 sc0 ls0 ws0">to increase capacity for clinical training, address
                future <span class="_ _0"></span>workforce needs, increase research
            </div>
            <div class="t m0 x6 h5 y2c ff2 fs2 fc1 sc0 ls0 ws0">opportunities, and facilitate entrepreneurial
                opportunities.
            </div>
        </div>
        <div class="pi" data-data='{"ctm":[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}'></div>
    </div>
    <div id="pf3" class="pf w0 h0" data-page-no="3">
        <div class="pc pc3 w0 h0"><img class="bi x5 y5 w1 h3" alt="" src="bg3.png"/>
            <div class="t m0 x3 h4 y6 ff3 fs1 fc1 sc0 ls1 ws0">Blueprint for Acade<span class="_ _0"></span>mic
                Excellence
            </div>
            <div class="t m0 x3 h5 y7 ff2 fs2 fc1 sc0 ls0 ws0">College of Nursing</div>
            <div class="t m0 x3 h5 y2d ff2 fs2 fc1 sc0 ls0 ws0">Jeannette Andrews, Dean</div>
            <div class="t m0 x3 h5 y2e ff2 fs2 fc1 sc0 ls0 ws0">Executive Summary<span class="_ _2"> </span>2</div>
            <div class="t m0 x3 h5 y2f ff2 fs2 fc1 sc0 ls0 ws0">Blueprint for Academic Excellence<span
                    class="_ _3"> </span>3
            </div>
            <div class="t m0 x8 h5 y30 ff2 fs2 fc1 sc0 ls0 ws0">Mission<span class="_ _4"> </span>4</div>
            <div class="t m0 x8 h5 y31 ff2 fs2 fc1 sc0 ls0 ws0">Vision<span class="_ _5"> </span>4</div>
            <div class="t m0 x8 h5 y32 ff2 fs2 fc1 sc0 ls0 ws0">Values<span class="_ _6"> </span>4</div>
            <div class="t m0 x8 h5 y33 ff2 fs2 fc1 sc0 ls0 ws0">Goals<span class="_ _7"> </span>4</div>
            <div class="t m0 x3 h5 y34 ff2 fs2 fc1 sc0 ls0 ws0">Outcomes – 2015-2016 Academic Year<span
                    class="_ _8"> </span>6
            </div>
            <div class="t m0 x8 h5 y35 ff2 fs2 fc1 sc0 ls0 ws0">Faculty Development &amp; Activities<span
                    class="_ _9"> </span>6
            </div>
            <div class="t m0 x8 h5 y36 ff2 fs2 fc1 sc0 ls0 ws0">Faculty Awards<span class="_ _a"> </span>6</div>
            <div class="t m0 x8 h5 y37 ff2 fs2 fc1 sc0 ls0 ws0">Personnel – Faculty<span class="_ _b"> </span>7</div>
            <a class="l" href="#pf2" data-dest-detail='[2,"XYZ",72,720,null]'>
                <div class="d m1"
                     style="border-style:none;position:absolute;left:533.924988px;bottom:595.314026px;width:5.575012px;height:13.427979px;background-color:rgba(255,255,255,0.000001);"></div>
            </a><a class="l" href="#pf3" data-dest-detail='[3,"XYZ",72,720,null]'>
                <div class="d m1"
                     style="border-style:none;position:absolute;left:533.924988px;bottom:576.885986px;width:5.575012px;height:13.427979px;background-color:rgba(255,255,255,0.000001);"></div>
            </a><a class="l" href="#pf4" data-dest-detail='[4,"XYZ",72,698,null]'>
                <div class="d m1"
                     style="border-style:none;position:absolute;left:533.924988px;bottom:558.458008px;width:5.575012px;height:13.427979px;background-color:rgba(255,255,255,0.000001);"></div>
            </a><a class="l" href="#pf4" data-dest-detail='[4,"XYZ",72,620,null]'>
                <div class="d m1"
                     style="border-style:none;position:absolute;left:533.924988px;bottom:540.030029px;width:5.575012px;height:13.427979px;background-color:rgba(255,255,255,0.000001);"></div>
            </a><a class="l" href="#pf4" data-dest-detail='[4,"XYZ",72,541,null]'>
                <div class="d m1"
                     style="border-style:none;position:absolute;left:533.924988px;bottom:521.602051px;width:5.575012px;height:13.427979px;background-color:rgba(255,255,255,0.000001);"></div>
            </a><a class="l" href="#pf4" data-dest-detail='[4,"XYZ",72,463,null]'>
                <div class="d m1"
                     style="border-style:none;position:absolute;left:533.924988px;bottom:503.173981px;width:5.575012px;height:13.428009px;background-color:rgba(255,255,255,0.000001);"></div>
            </a><a class="l" href="#pf6" data-dest-detail='[6,"XYZ",72,720,null]'>
                <div class="d m1"
                     style="border-style:none;position:absolute;left:533.924988px;bottom:484.746002px;width:5.575012px;height:13.428009px;background-color:rgba(255,255,255,0.000001);"></div>
            </a><a class="l" href="#pf6" data-dest-detail='[6,"XYZ",72,673,null]'>
                <div class="d m1"
                     style="border-style:none;position:absolute;left:533.924988px;bottom:466.317993px;width:5.575012px;height:13.428009px;background-color:rgba(255,255,255,0.000001);"></div>
            </a><a class="l" href="#pf6" data-dest-detail='[6,"XYZ",72,417,null]'>
                <div class="d m1"
                     style="border-style:none;position:absolute;left:533.924988px;bottom:447.889984px;width:5.575012px;height:13.428009px;background-color:rgba(255,255,255,0.000001);"></div>
            </a><a class="l" href="#pf7" data-dest-detail='[7,"XYZ",72,720,null]'>
                <div class="d m1"
                     style="border-style:none;position:absolute;left:533.924988px;bottom:429.462006px;width:5.575012px;height:13.428009px;background-color:rgba(255,255,255,0.000001);"></div>
            </a></div>
        <div class="pi" data-data='{"ctm":[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}'></div>
    </div>
    <div id="pf4" class="pf w0 h0" data-page-no="4">
        <div class="pc pc4 w0 h0"><img class="bi x5 y38 w1 h7" alt="" src="bg4.png"/>
            <div class="t m0 x3 h8 y39 ff5 fs3 fc1 sc1 ls1 ws0">Mission</div>
            <div class="t m0 x3 h5 y3a ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_MissionVisionValues
                ‘MISSION_STATEMENT’&gt;</div>
            <div class="t m0 x3 h8 y3b ff5 fs3 fc1 sc1 ls1 ws0">Vision</div>
            <div class="t m0 x3 h5 y3c ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_MissionVisionValues
                ‘VISION_STATEMENT&gt;</div>
            <div class="t m0 x3 h8 y3d ff5 fs3 fc1 sc1 ls1 ws0">Values</div>
            <div class="t m0 x3 h5 y3e ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_MissionVisionValues
                ‘VALUES_STATEMENT’&gt;</div>
            <div class="t m0 x3 h8 y3f ff5 fs3 fc1 sc1 ls1 ws0">Goals</div>
            <div class="t m0 x3 h6 y40 ff1 fs2 fc1 sc0 ls0 ws0">Goal 1 - &lt;merge BP_UnitGoals PRIORITY_GOAL_AY = 1
                ‘UNIT_GOAL_TITLE’&gt;</div>
            <div class="c x9 y41 w2 h9">
                <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Linkage to</div>
                <div class="t m0 xa h6 y43 ff1 fs2 fc1 sc0 ls0 ws0">University Goal</div>
            </div>
            <div class="c xb y41 w3 h9">
                <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_UnitGoals PRIORITY_GOAL_AY = 1
                    ‘LINK_UNIV_GOAL’&gt;</div>
            </div>
            <div class="c x9 y44 w2 ha">
                <div class="t m0 xa h6 y45 ff1 fs2 fc1 sc0 ls0 ws0">Goal</div>
            </div>
            <div class="c xb y44 w3 ha">
                <div class="t m0 xa h5 y45 ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_UnitGoals PRIORITY_GOAL_AY = 1
                    ‘GOAL_STATEMENT’&gt;</div>
            </div>
            <div class="c x9 y46 w2 hb">
                <div class="t m0 xa h6 y47 ff1 fs2 fc1 sc0 ls0 ws0">Alignment with</div>
                <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Mission, Vision,</div>
                <div class="t m0 xa h6 y43 ff1 fs2 fc1 sc0 ls0 ws0">and Values</div>
            </div>
            <div class="c xb y46 w3 hb">
                <div class="t m0 xa h5 y47 ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_UnitGoals PRIORITY_GOAL_AY = 1
                    ‘GOAL_ALIGNMENT’&gt;</div>
            </div>
            <div class="c x9 y48 w2 h9">
                <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Status</div>
            </div>
            <div class="c xb y48 w3 h9">
                <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL
                    <span class="_ _0"></span>and insert
                </div>
                <div class="t m0 xa h5 y43 ff2 fs2 fc1 sc0 ls0 ws0">‘GOAL_STATUS’&gt;</div>
            </div>
            <div class="c x9 y49 w2 h9">
                <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Achievements</div>
            </div>
            <div class="c xb y49 w3 h9">
                <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL
                    <span class="_ _0"></span>and insert
                </div>
                <div class="t m0 xa h5 y43 ff2 fs2 fc1 sc0 ls0 ws0">‘GOAL_ACHIEVEMENTS’&gt;</div>
            </div>
            <div class="c x9 y4a w2 h9">
                <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Resources Utilized</div>
            </div>
            <div class="c xb y4a w3 h9">
                <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL
                    <span class="_ _0"></span>and insert
                </div>
                <div class="t m0 xa h5 y43 ff2 fs2 fc1 sc0 ls0 ws0">‘GOAL_RSRCS_UTLZD’&gt;</div>
            </div>
            <div class="c x9 y4b w2 h9">
                <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Continuation</div>
            </div>
            <div class="c xb y4b w3 h9">
                <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL
                    <span class="_ _0"></span>and insert
                </div>
                <div class="t m0 xa h5 y43 ff2 fs2 fc1 sc0 ls0 ws0">‘GOAL_CONTINUATION’&gt;</div>
            </div>
            <div class="c x9 y4c w2 h9">
                <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Resources Needed</div>
            </div>
            <div class="c xb y4c w3 h9">
                <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL
                    <span class="_ _0"></span>and insert
                </div>
                <div class="t m0 xa h5 y43 ff2 fs2 fc1 sc0 ls0 ws0">‘GOAL_RSRCS_NEEDED’&gt;</div>
            </div>
            <div class="c x9 y4d w2 h9">
                <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Notes</div>
            </div>
            <div class="c xb y4d w3 h9">
                <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL
                    <span class="_ _0"></span>and insert
                </div>
                <div class="t m0 xa h5 y43 ff2 fs2 fc1 sc0 ls0 ws0">‘GOAL_NOTES’&gt;</div>
            </div>
        </div>
        <div class="pi" data-data='{"ctm":[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}'></div>
    </div>
    <div id="pf5" class="pf w0 h0" data-page-no="5">
        <div class="pc pc5 w0 h0"><img class="bi x9 y4e w4 hc" alt="" src="bg5.png"/>
            <div class="t m0 x3 h6 y4f ff1 fs2 fc1 sc0 ls0 ws0">Goal 2 - &lt;merge BP_UnitGoals PRIORITY_GOAL_AY = 2
                ‘UNIT_GOAL_TITLE’&gt;</div>
            <div class="c x9 y50 w2 h9">
                <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Linkage to</div>
                <div class="t m0 xa h6 y43 ff1 fs2 fc1 sc0 ls0 ws0">University Goal</div>
            </div>
            <div class="c xb y50 w3 h9">
                <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_UnitGoals PRIORITY_GOAL_AY = 1
                    ‘LINK_UNIV_GOAL’&gt;</div>
            </div>
            <div class="c x9 y51 w2 ha">
                <div class="t m0 xa h6 y45 ff1 fs2 fc1 sc0 ls0 ws0">Goal</div>
            </div>
            <div class="c xb y51 w3 ha">
                <div class="t m0 xa h5 y45 ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_UnitGoals PRIORITY_GOAL_AY = 1
                    ‘GOAL_STATEMENT’&gt;</div>
            </div>
            <div class="c x9 y52 w2 hb">
                <div class="t m0 xa h6 y47 ff1 fs2 fc1 sc0 ls0 ws0">Alignment with</div>
                <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Mission, Vision,</div>
                <div class="t m0 xa h6 y43 ff1 fs2 fc1 sc0 ls0 ws0">and Values</div>
            </div>
            <div class="c xb y52 w3 hb">
                <div class="t m0 xa h5 y47 ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_UnitGoals PRIORITY_GOAL_AY = 1
                    ‘GOAL_ALIGNMENT’&gt;</div>
            </div>
            <div class="c x9 y53 w2 h9">
                <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Status</div>
            </div>
            <div class="c xb y53 w3 h9">
                <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL
                    <span class="_ _0"></span>and insert
                </div>
                <div class="t m0 xa h5 y43 ff2 fs2 fc1 sc0 ls0 ws0">‘GOAL_STATUS’&gt;</div>
            </div>
            <div class="c x9 y54 w2 h9">
                <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Achievements</div>
            </div>
            <div class="c xb y54 w3 h9">
                <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL
                    <span class="_ _0"></span>and insert
                </div>
                <div class="t m0 xa h5 y43 ff2 fs2 fc1 sc0 ls0 ws0">‘GOAL_ACHIEVEMENTS’&gt;</div>
            </div>
            <div class="c x9 y55 w2 h9">
                <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Resources Utilized</div>
            </div>
            <div class="c xb y55 w3 h9">
                <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL
                    <span class="_ _0"></span>and insert
                </div>
                <div class="t m0 xa h5 y43 ff2 fs2 fc1 sc0 ls0 ws0">‘GOAL_RSRCS_UTLZD’&gt;</div>
            </div>
            <div class="c x9 y56 w2 h9">
                <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Continuation</div>
            </div>
            <div class="c xb y56 w3 h9">
                <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL
                    <span class="_ _0"></span>and insert
                </div>
                <div class="t m0 xa h5 y43 ff2 fs2 fc1 sc0 ls0 ws0">‘GOAL_CONTINUATION’&gt;</div>
            </div>
            <div class="c x9 y57 w2 h9">
                <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Resources Needed</div>
            </div>
            <div class="c xb y57 w3 h9">
                <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL
                    <span class="_ _0"></span>and insert
                </div>
                <div class="t m0 xa h5 y43 ff2 fs2 fc1 sc0 ls0 ws0">‘GOAL_RSRCS_NEEDED’&gt;</div>
            </div>
            <div class="c x9 y58 w2 h9">
                <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Notes</div>
            </div>
            <div class="c xb y58 w3 h9">
                <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL
                    <span class="_ _0"></span>and insert
                </div>
                <div class="t m0 xa h5 y43 ff2 fs2 fc1 sc0 ls0 ws0">‘GOAL_NOTES’&gt;</div>
            </div>
        </div>
        <div class="pi" data-data='{"ctm":[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}'></div>
    </div>
    <div id="pf6" class="pf w0 h0" data-page-no="6">
        <div class="pc pc6 w0 h0"><img class="bi x5 y59 w5 hd" alt="" src="bg6.png"/>
            <div class="t m0 x3 h4 y6 ff3 fs1 fc1 sc0 ls1 ws0">Outcomes – 2015-2016 Ac<span class="_ _0"></span>ademic
                Year
            </div>
            <div class="t m0 x3 h8 y5a ff5 fs3 fc1 sc1 ls1 ws0">Faculty Development &amp; Activiti<span
                    class="_ _0"></span>es
            </div>
            <div class="t m0 x3 h5 y5b ff1 fs2 fc1 sc0 ls0 ws0">Development. <span class="ff2">College efforts and initiatives for faculty<span
                        class="_ _0"></span> development, including investments, activities, </span></div>
            <div class="t m0 x3 h5 y5c ff2 fs2 fc1 sc0 ls0 ws0">incentives, objectives, and outcomes.</div>
            <div class="t m0 x6 h5 y5d ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge AC_FacultyInfo ‘FACULTY_DEVELOPMENT’&gt;</div>
            <div class="t m0 x3 h5 y5e ff1 fs2 fc1 sc0 ls0 ws0">Creative Activity.<span class="ff2"> Significant artistic, creative,<span
                        class="_ _0"></span> and performance activities of faculty.</span></div>
            <div class="t m0 x6 h5 y5f ff2 fs2 fc1 sc0 ls0 ws0">&lt;merge AC_FacultyInfo ‘CREATIVE_ACTIVITY’</div>
            <div class="t m0 x3 h5 y60 ff1 fs2 fc1 sc0 ls0 ws0">Supplemental Info.<span class="ff2">  Additional information provided <span
                        class="_ _0"></span>by the College for Faculty Development &amp; </span></div>
            <div class="t m0 x3 h5 y61 ff2 fs2 fc1 sc0 ls0 ws0">Activities is provided in Appendix 1.</div>
            <div class="t m0 x6 h5 y62 ff2 fs2 fc2 sc0 ls0 ws0">&lt;link to Appendix 1, insert Appendix 1..99 at end of
                report, <span class="_ _0"></span>in numeric sequence&gt;.
            </div>
            <div class="t m0 x3 h8 y63 ff5 fs3 fc1 sc1 ls1 ws0">Faculty Awards</div>
            <div class="t m0 x3 h5 y64 ff1 fs2 fc1 sc0 ls0 ws0">National Awards &amp; Recognition.<span class="ff2">  Among others, <span
                        class="_ _0"></span>the Dean has selected to highlight the following awards </span></div>
            <div class="t m0 x3 h5 y65 ff2 fs2 fc1 sc0 ls0 ws0">and recognition received by the faculty during the <span
                    class="_ _0"></span>&lt;insert AcademicYears ‘ACAD_YEAR_DESC’&gt;.
            </div>
            <div class="t m0 x6 h5 y66 ff2 fs2 fc2 sc0 ls0 ws0">&lt;merge AC_FacultyAwards as follows… note that
                DATE_AWARDED<span class="_ _0"></span> has been intentionally
            </div>
            <div class="t m0 x6 h5 y67 ff2 fs2 fc2 sc0 ls0 ws0">omitted; for now, insert as many as entered for the Unit&gt;</div>
            <div class="c x6 y68 w6 ha">
                <div class="t m0 xa h6 y45 ff1 fs2 fc0 sc0 ls0 ws0">Recipient(s)</div>
            </div>
            <div class="c xc y68 w7 ha">
                <div class="t m0 xa h6 y45 ff1 fs2 fc0 sc0 ls0 ws0">Award Type</div>
            </div>
            <div class="c xd y68 w8 ha">
                <div class="t m0 xa h6 y45 ff1 fs2 fc0 sc0 ls0 ws0">Award</div>
            </div>
            <div class="c xe y68 w9 ha">
                <div class="t m0 xa h6 y45 ff1 fs2 fc0 sc0 ls0 ws0">Organization</div>
            </div>
            <div class="c x6 y69 w6 he">
                <div class="t m0 xa hf y6a ff1 fs4 fc1 sc0 ls0 ws0">&lt;RECIPIENT_NAME_LAST,</div>
                <div class="t m0 xa hf y6b ff1 fs4 fc1 sc0 ls0 ws0">RECIPIENT_NAME_FIRST&gt;</div>
            </div>
            <div class="c xc y69 w7 he">
                <div class="t m0 xa hf y6a ff1 fs4 fc1 sc0 ls0 ws0">&lt;AWARD_TYPE&gt;</div>
            </div>
            <div class="c xd y69 w8 he">
                <div class="t m0 xa hf y6a ff1 fs4 fc1 sc0 ls0 ws0">&lt;AWARD_TITLE&gt;</div>
            </div>
            <div class="c xe y69 w9 he">
                <div class="t m0 xa hf y6a ff1 fs4 fc1 sc0 ls0 ws0">&lt;AWARDING_ORG&gt;</div>
            </div>
            <div class="c x6 y6c w6 he">
                <div class="t m0 xa hf y6a ff1 fs4 fc1 sc0 ls0 ws0">&lt;RECIPIENT_NAME_LAST,</div>
                <div class="t m0 xa hf y6b ff1 fs4 fc1 sc0 ls0 ws0">RECIPIENT_NAME_FIRST&gt;</div>
            </div>
            <div class="c xc y6c w7 he">
                <div class="t m0 xa hf y6a ff1 fs4 fc1 sc0 ls0 ws0">&lt;AWARD_TYPE&gt;</div>
            </div>
            <div class="c xd y6c w8 he">
                <div class="t m0 xa hf y6a ff1 fs4 fc1 sc0 ls0 ws0">&lt;AWARD_TITLE&gt;</div>
            </div>
            <div class="c xe y6c w9 he">
                <div class="t m0 xa hf y6a ff1 fs4 fc1 sc0 ls0 ws0">&lt;AWARDING_ORG&gt;</div>
            </div>
            <div class="c x6 y6d w6 he">
                <div class="t m0 xa hf y6a ff1 fs4 fc1 sc0 ls0 ws0">&lt;RECIPIENT_NAME_LAST,</div>
                <div class="t m0 xa hf y6b ff1 fs4 fc1 sc0 ls0 ws0">RECIPIENT_NAME_FIRST&gt;</div>
            </div>
            <div class="c xc y6d w7 he">
                <div class="t m0 xa hf y6a ff1 fs4 fc1 sc0 ls0 ws0">&lt;AWARD_TYPE&gt;</div>
            </div>
            <div class="c xd y6d w8 he">
                <div class="t m0 xa hf y6a ff1 fs4 fc1 sc0 ls0 ws0">&lt;AWARD_TITLE&gt;</div>
            </div>
            <div class="c xe y6d w9 he">
                <div class="t m0 xa hf y6a ff1 fs4 fc1 sc0 ls0 ws0">&lt;AWARDING_ORG&gt;</div>
            </div>
        </div>
        <div class="pi" data-data='{"ctm":[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}'></div>
    </div>
    <div id="pf7" class="pf w0 h0" data-page-no="7">
        <div class="pc pc7 w0 h0"><img class="bi x5 y6e w5 h10" alt="" src="bg7.png"/>
            <div class="t m0 x3 h8 y6f ff5 fs3 fc1 sc1 ls1 ws0">Personnel – Faculty</div>
            <div class="t m0 x3 h5 y70 ff2 fs2 fc1 sc0 ls0 ws0">Composition of the faculty during the 2015-2016<span
                    class="_ _0"></span> Academic Year, as compared to the previous 2 years.
            </div>
            <div class="t m0 x3 h6 y3a ff1 fs2 fc1 sc0 ls0 ws0">Population by Type</div>
            <div class="t m0 x6 h5 y71 ff2 fs2 fc2 sc0 ls0 ws0">&lt;insert values from IR_AC_FacultyPop&gt;</div>
            <div class="c xf y72 wa ha">
                <div class="t m0 x10 h11 y45 ff5 fs2 fc1 sc1 ls0 ws0">Academic Year</div>
            </div>
            <div class="c xf y73 wb ha">
                <div class="t m0 x11 h11 y45 ff5 fs2 fc1 sc1 ls0 ws0">2015-2016</div>
            </div>
            <div class="c x12 y73 wb ha">
                <div class="t m0 x11 h11 y45 ff5 fs2 fc1 sc1 ls0 ws0">2014-2015</div>
            </div>
            <div class="c x13 y73 wc ha">
                <div class="t m0 x11 h11 y45 ff5 fs2 fc1 sc1 ls0 ws0">2013-2014</div>
            </div>
            <div class="c x6 y74 wd h12">
                <div class="t m0 xa h11 y75 ff5 fs2 fc1 sc1 ls0 ws0">Faculty Count Tenure</div>
            </div>
            <div class="c x6 y76 wd h12">
                <div class="t m0 xa h11 y75 ff5 fs2 fc1 sc1 ls0 ws0">Faculty Count Tenure Track</div>
            </div>
            <div class="c x6 y77 wd h12">
                <div class="t m0 xa h11 y75 ff5 fs2 fc1 sc1 ls0 ws0">Faculty Count Non-Tenture-Track</div>
            </div>
            <div class="c x6 y78 wd h12">
                <div class="t m0 xa h11 y75 ff5 fs2 fc1 sc1 ls0 ws0">Faculty Count Adjunct</div>
            </div>
            <div class="c x6 y79 wd h12">
                <div class="t m0 xa h11 y75 ff5 fs2 fc1 sc1 ls0 ws0">Faculty Count Affiliates</div>
            </div>
            <div class="c x6 y7a wd h12">
                <div class="t m0 xa h11 y75 ff5 fs2 fc1 sc1 ls0 ws0">Faculty Hired</div>
            </div>
            <div class="c x6 y7b wd h12">
                <div class="t m0 xa h11 y75 ff5 fs2 fc1 sc1 ls0 ws0">Faculty Unfilled Vacancies</div>
            </div>
            <div class="c x6 y7c wd h12">
                <div class="t m0 xa h11 y75 ff5 fs2 fc1 sc1 ls0 ws0">Faculty Retention Packages</div>
            </div>
            <div class="t m0 x3 h6 y7d ff1 fs2 fc1 sc0 ls0 ws0">Population Diversity – Gender, Race/Ethnicity,<span
                    class="_ _0"></span> and Citizenship
            </div>
            <div class="t m0 x6 h5 y7e ff2 fs2 fc1 sc0 ls0 ws0">USC follows the federal self-identification and
                reporting<span class="_ _0"></span> categories for Race/Ethnicity. The
            </div>
            <div class="t m0 x6 h5 y7f ff2 fs2 fc1 sc0 ls0 ws0">values presented below reflect our estimation of the
                <span class="_ _0"></span>categories our faculty would be identified
            </div>
            <div class="t m0 x6 h5 y80 ff2 fs2 fc1 sc0 ls0 ws0">as, under the federal methodology. As such, each
                individual <span class="_ _0"></span>is reported in one category only;
            </div>
            <div class="t m0 x6 h5 y81 ff2 fs2 fc1 sc0 ls0 ws0">non-citizens are not included in Race/Ethnicity, and
                <span class="_ _0"></span>individuals who select Hispanic/Latino are
            </div>
            <div class="t m0 x6 h5 y82 ff2 fs2 fc1 sc0 ls0 ws0">reported as such, regardless of their selections on
                race.
            </div>
            <div class="c x14 y83 we ha">
                <div class="t m0 x7 h11 y45 ff5 fs2 fc1 sc1 ls0 ws0">Academic Year</div>
            </div>
            <div class="c x14 y84 wf ha">
                <div class="t m0 x11 h11 y45 ff5 fs2 fc1 sc1 ls0 ws0">2015-2016</div>
            </div>
            <div class="c x15 y84 wf ha">
                <div class="t m0 x11 h11 y45 ff5 fs2 fc1 sc1 ls0 ws0">2014-2015</div>
            </div>
            <div class="c x16 y84 w10 ha">
                <div class="t m0 x11 h11 y45 ff5 fs2 fc1 sc1 ls0 ws0">2013-2014</div>
            </div>
            <div class="c x6 y85 w11 h12">
                <div class="t m0 xa h11 y75 ff5 fs2 fc0 sc2 ls0 ws0">Gender</div>
            </div>
            <div class="c x6 y86 w11 h12">
                <div class="t m0 x17 h11 y75 ff5 fs2 fc1 sc1 ls0 ws0">Female</div>
            </div>
            <div class="c x6 y87 w11 h12">
                <div class="t m0 x17 h11 y75 ff5 fs2 fc1 sc1 ls0 ws0">Male</div>
            </div>
            <div class="c x6 y88 w11 h12">
                <div class="t m0 xa h11 y75 ff5 fs2 fc0 sc2 ls0 ws0">Race/Ethnicity</div>
            </div>
            <div class="c x6 y89 w11 h12">
                <div class="t m0 x17 h11 y75 ff5 fs2 fc1 sc1 ls0 ws0">African American or Black</div>
            </div>
            <div class="c x6 y8a w11 h12">
                <div class="t m0 x17 h11 y75 ff5 fs2 fc1 sc1 ls0 ws0">American Indian or Alaska Native</div>
            </div>
            <div class="c x6 y8b w11 h12">
                <div class="t m0 x17 h11 y75 ff5 fs2 fc1 sc1 ls0 ws0">Asian</div>
            </div>
            <div class="c x6 y8c w11 h12">
                <div class="t m0 x17 h11 y75 ff5 fs2 fc1 sc1 ls0 ws0">Hispanic</div>
            </div>
            <div class="c x6 y8d w11 h12">
                <div class="t m0 x17 h11 y75 ff5 fs2 fc1 sc1 ls0 ws0">Native Hawaiian/Other Pacific Islander</div>
            </div>
            <div class="c x6 y8e w11 h12">
                <div class="t m0 x17 h11 y75 ff5 fs2 fc1 sc1 ls0 ws0">White</div>
            </div>
        </div>
        <div class="pi" data-data='{"ctm":[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}'></div>
    </div>
    <div id="pf8" class="pf w0 h0" data-page-no="8">
        <div class="pc pc8 w0 h0"><img class="bi x6 y8f w12 h13" alt="" src="bg8.png"/>
            <div class="c x6 y90 w11 h12">
                <div class="t m0 x17 h11 y75 ff5 fs2 fc1 sc1 ls0 ws0">Two or More Races</div>
            </div>
            <div class="c x6 y91 w11 h12">
                <div class="t m0 x17 h11 y75 ff5 fs2 fc1 sc1 ls0 ws0">Race/Ethnicity Unknown</div>
            </div>
            <div class="c x6 y92 w11 h12">
                <div class="t m0 x17 h11 y75 ff5 fs2 fc1 sc1 ls0 ws0">Nonresident Alien (Non-Citizen)</div>
            </div>
        </div>
        <div class="pi" data-data='{"ctm":[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}'></div>
    </div>
    <div id="pf9" class="pf w0 h0" data-page-no="9">
        <div class="pc pc9 w0 h0">
            <div class="t m0 x3 h5 y4f ff2 fs2 fc1 sc0 ls0 ws0">Student Enrollment</div>
            <div class="t m0 x3 h5 y93 ff2 fs2 fc1 sc0 ls0 ws0"></div>
            <div class="t m0 x3 h5 y94 ff2 fs2 fc1 sc0 ls0 ws0">Population by Level</div>
            <div class="t m0 x3 h5 y95 ff2 fs2 fc1 sc0 ls0 ws0">Portion of USC Columbia Enrollment</div>
            <div class="t m0 x3 h5 y96 ff2 fs2 fc1 sc0 ls0 ws0">Race/Ethnicity &amp; Citizenship</div>
        </div>
        <div class="pi" data-data='{"ctm":[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}'></div>
    </div>
</div>
<div class="loading-indicator">
</html>
