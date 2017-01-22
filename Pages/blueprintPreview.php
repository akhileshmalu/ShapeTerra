

<link href="Css/templateTabs.css" rel="stylesheet" type="text/css"/>
<link href="Css/approvebp.css" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" href="../Pages/blueprint/Blueprinthtml/blueprint.css"/>
<link rel="stylesheet" href="../Pages/blueprint/Blueprinthtml/base.min.css"/>
<link rel="stylesheet" href="../Pages/blueprint/Blueprinthtml/fancy.min.css"/>
<link rel="stylesheet" href="../Pages/blueprint/Blueprinthtml/main.css"/>
<link rel="stylesheet" href="Css/blueprintPreview.css"/>
<script src="../Pages/blueprint/Blueprinthtml/compatibility.min.js"></script>
<script src="../Pages/blueprint/Blueprinthtml/theViewer.min.js"></script>

<script>
    try {
        theViewer.defaultViewer = new theViewer.Viewer({});
    } catch (e) {
    }
</script>


<div id="page-container" class="col-lg-12" style="min-height: 810px">
  <div id="pf1" class="pf w0 h0" data-page-no="1">
    <h1>Blueprint for Academic Excellence</h1>
    <h1>&lt;OU_NAME&gt;</h1>
    <h1>&lt;ACADEMIC_YEAR_DESC&gt;</h1>

    <img  src="../Resources/Images/USC_Web_Linear_RGB.png">
  </div>

  <div id="pf2" class="pf w0 h0" data-page-no="2">
    <h2>Executive Summary</h2>
    <p>&lt;Executive summary&gt;</p>
  </div>

  <div id="pf3" class="pf w0 h0" data-page-no="3">
    <h2>Blueprint for Academic Excellence</h2>
    <p>&lt;OU_NAME&gt;</p>
    <p>Hossein Haj-Hariri, Dean</p>

    <br />
    <p style="text-align:left;">Executive Summary <span style="float:right;">2</span></p>
    <p style="text-align:left;">Blueprint for Academic Excellence <span style="float:right;">3</span></p>
    <p class="indent" style="text-align:left;">Mission <span style="float:right;">4</span></p>
    <p class="indent" style="text-align:left;">Vision <span style="float:right;">4</span></p>
    <p class="indent" style="text-align:left;">Values <span style="float:right;">4</span></p>
    <p class="indent" style="text-align:left;">Goals <span style="float:right;">4</span></p>
    <p style="text-align:left;">Outcomes – 2015-2016 Academic Year <span style="float:right;">6</span></p>
    <p class="indent" style="text-align:left;">Faculty Development &amp; Activities <span style="float:right;">6</span></p>
    <p class="indent" style="text-align:left;">Faculty Awards <span style="float:right;">6</span></p>
    <p class="indent" style="text-align:left;">Personnel – Faculty <span style="float:right;">7</span></p>
  </div>

   <div id="pf4" class="pf w0 h0" data-page-no="4">
    <h3>Mission</h3>
    <p>&lt;merge BP_MissionVisionValues ‘MISSION_STATEMENT’&gt;</p>

    <h3>Vision</h3>
    <p>&lt;merge BP_MissionVisionValues ‘VISION_STATEMENT&gt;</p>

    <h3>Values</h3>
    <p>&lt;merge BP_MissionVisionValues ‘VALUES_STATEMENT’&gt;</p>

    <h3>Goals</h3>
    <p><b>Goal 1 - &lt;merge BP_UnitGoals PRIORITY_GOAL_AY = 1 ‘UNIT_GOAL_TITLE’&gt;</b></p>
    <table>
      <tr>
        <td><b>Linkage to University Goal</b></td>
        <td>&lt;merge BP_UnitGoals PRIORITY_GOAL_AY = 1 ‘LINK_UNIV_GOAL’&gt;</td>
      </tr>
      <tr>
        <td><b>Goal</b></td>
        <td>&lt;merge BP_UnitGoals PRIORITY_GOAL_AY = 1 ‘GOAL_STATEMENT’&gt;</td>
      </tr>
      <tr>
        <td><b>Alignment with Mission, Vision, and Values</b></td>
        <td>&lt;merge BP_UnitGoals PRIORITY_GOAL_AY = 1 ‘GOAL_ALIGNMENT’&gt;</td>
      </tr>
      <tr>
        <td><b>Status</b></td>
        <td>&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL and insert ‘GOAL_STATUS’&gt;</td>
      </tr>
      <tr>
        <td><b>Achievements</b></td>
        <td>&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL and insert ‘GOAL_ACHIEVEMENTS’&gt;</td>
      </tr>
      <tr>
        <td><b>Resources Utilized</b></td>
        <td>&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL and insert ‘GOAL_RSRCS_UTLZD’&gt;</td>
      </tr>
      <tr>
        <td><b>Continuation</b></td>
        <td>&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL and insert ‘GOAL_CONTINUATION’&gt;</td>
      </tr>
      <tr>
        <td><b>Resources Needed</b></td>
        <td>&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL and insert ‘GOAL_RSRCS_NEEDED’&gt;</td>
      </tr>
      <tr>
        <td><b>Notes</b></td>
        <td>&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL and insert ‘GOAL_NOTES’&gt;</td>
      </tr>
    </table>  
  </div>

  <div id="pf5" class="pf w0 h0" data-page-no="5">
  <p><b>Goal 2 - &lt;merge BP_UnitGoals PRIORITY_GOAL_AY = 1 ‘UNIT_GOAL_TITLE’&gt;</b></p>
    <table>
      <tr>
        <td><b>Linkage to University Goal</b></td>
        <td>&lt;merge BP_UnitGoals PRIORITY_GOAL_AY = 1 ‘LINK_UNIV_GOAL’&gt;</td>
      </tr>
      <tr>
        <td><b>Goal</b></td>
        <td>&lt;merge BP_UnitGoals PRIORITY_GOAL_AY = 1 ‘GOAL_STATEMENT’&gt;</td>
      </tr>
      <tr>
        <td><b>Alignment with Mission, Vision, and Values</b></td>
        <td>&lt;merge BP_UnitGoals PRIORITY_GOAL_AY = 1 ‘GOAL_ALIGNMENT’&gt;</td>
      </tr>
      <tr>
        <td><b>Status</b></td>
        <td>&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL and insert ‘GOAL_STATUS’&gt;</td>
      </tr>
      <tr>
        <td><b>Achievements</b></td>
        <td>&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL and insert ‘GOAL_ACHIEVEMENTS’&gt;</td>
      </tr>
      <tr>
        <td><b>Resources Utilized</b></td>
        <td>&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL and insert ‘GOAL_RSRCS_UTLZD’&gt;</td>
      </tr>
      <tr>
        <td><b>Continuation</b></td>
        <td>&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL and insert ‘GOAL_CONTINUATION’&gt;</td>
      </tr>
      <tr>
        <td><b>Resources Needed</b></td>
        <td>&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL and insert ‘GOAL_RSRCS_NEEDED’&gt;</td>
      </tr>
      <tr>
        <td><b>Notes</b></td>
        <td>&lt;merge BP_UnitGoalOutcomes match on ID_UNIT_GOAL and insert ‘GOAL_NOTES’&gt;</td>
      </tr>
    </table>  
  </div>

  <div id="pf6" class="pf w0 h0" data-page-no="6">
    <h2>Outcomes – 2015-2016 Academic Year</h2>
    <h3>Faculty Development &amp; Activities</h3>
    <p><b>Development.</b> College efforts and initiatives for faculty development, including investments, activities, incentives, objectives, and outcomes.</p>

    <p class="indent">&lt;merge AC_FacultyInfo ‘FACULTY_DEVELOPMENT’&gt;</p>

    <p><b>Creative Activity.</b> Significant artistic, creative, and performance activities of faculty.</p>

    <p class="indent">&lt;merge AC_FacultyInfo ‘CREATIVE_ACTIVITY’&gt;</p>

    <p><b>Supplemental Info.</b> Additional information provided by the College for Faculty Development & Activities is provided in Appendix 1.</p>

    <p class="indent">&lt;link to Appendix 1, insert Appendix 1..99 at end of report, in numeric sequence&gt;</p>

    <h3>Faculty Awards</h3>
    <p><b>National Awards &amp; Recognition.</b> Among others, the Dean has selected to highlight the following awards and recognition received by the faculty during the &lt;insert AcademicYears ‘ACAD_YEAR_DESC’&gt;.

    <p class="indent">&lt;merge AC_FacultyAwards as follows… note that DATE_AWARDED has been intentionally omitted; for now, insert as many as entered for the Unit&gt;</p>

    <table>
      <tr>
        <th>Recipient(s)</th>
        <th>Award Type</th>
        <th>Award </th>
        <th>Organization</th>
      </tr>
      <tr>
        <td>&lt;RECIPIENT_NAME_LAST&gt;, RECIPIENT_NAME_FIRST&gt;</td>
        <td>&lt;AWARD_TYPE&gt;</td>
        <td>&lt;AWARD_TITLE&gt;</td>
        <td>&lt;AWARDING_ORG&gt;</td>
      </tr>
      <tr>
        <td>&lt;RECIPIENT_NAME_LAST&gt;, RECIPIENT_NAME_FIRST&gt;</td>
        <td>&lt;AWARD_TYPE&gt;</td>
        <td>&lt;AWARD_TITLE&gt;</td>
        <td>&lt;AWARDING_ORG&gt;</td>
      </tr>
      <tr>
        <td>&lt;RECIPIENT_NAME_LAST&gt;, RECIPIENT_NAME_FIRST&gt;</td>
        <td>&lt;AWARD_TYPE&gt;</td>
        <td>&lt;AWARD_TITLE&gt;</td>
        <td>&lt;AWARDING_ORG&gt;</td>
      </tr>


      </tr>
    </table>
  </div>


  <div id="pf7" class="pf w0 h0" data-page-no="7">
    <h3>Personnel – Faculty</h3>
    <p>Composition of the faculty during the 2015-2016 Academic Year, as compared to the previous 2 years.</p>
    <p><b>Population by Type</b></p>
    <p class="indent">&lt;insert values from IR_AC_FacultyPop&gt;</p>

    <table>
      <tr>
        <td></td>
        <td></td>
        <td><b>Academic Year</b></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
          <td>2015-2016</td>
          <td>2014-2015</td>
          <td>2013-2014</td>
      </tr>
      <tr>
        <td><b>Faculty Count Tenure</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
        <td><b>Faculty Count Tenure track</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
        <td><b>Faculty Count Non-Tenture-Track</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
        <td><b>Faculty Count Adjunct</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
        <td><b>Faculty Count Affiliates</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
        <td><b>Faculty Hired</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
        <td><b>Faculty Unfilled Vacancies</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
        <td><b>Faculty Retention Packages</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
    </table>

    
  </div>

  <div id="pf8" class="pf w0 h0" data-page-no="8">
    <p><b>Population Diversity – Gender, Race/Ethnicity, and Citizenship</b></p>
    <p>USC follows the federal self-identification and reporting categories for Race/Ethnicity. The values presented below reflect our estimation of the categories our faculty would be identified as, under the federal methodology. As such, each individual is reported in one category only; non-citizens are not included in Race/Ethnicity, and individuals who select Hispanic/Latino are reported as such, regardless of their selections on race.</p>

    <table>
      <tr>
        <td></td>
        <td></td>
        <td><b>Academic Year</b></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
          <td>2015-2016</td>
          <td>2014-2015</td>
          <td>2013-2014</td>
      </tr>
      <tr>
        <td style="color:#8D0511"><b>Gender</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
        <td class="indent"><b>Female</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
        <td class="indent"><b>Male</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
        <td style="color:#8D0511"><b>Race/Ethnicity</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
        <td class="indent"><b>African American or Black</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
        <td class="indent"><b>American Indian or Alaska Native</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
        <td class="indent"><b>Asian</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
        <td class="indent"><b>Hispanic</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
        <td class="indent"><b>Native Hawaiian/Other Pacific Islander</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
        <td class="indent"><b>White</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
        <td class="indent"><b>Two or More Races</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
        <td class="indent"><b>Race/Ethnicity Unknown</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
        <td class="indent"><b>Nonresident Alien (Non-Citizen)</b></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
    </table>
  </div>

  <div id="pf9" class="pf w0 h0" data-page-no="9">
    <p>Student Enrollment</p>

    <p>Population by Level</p>

    <p>Portion of USC Columbia Enrollment</p>

    <p>Race/Ethnicity &amp; Citizenship</p>
  </div>


