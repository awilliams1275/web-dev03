<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>



<?php

//array is content from bulletin.fordham.edu/programs/index.html

$programurls=array('5-Year Integrated Teacher Education Track'=>'/undergraduate/special-academic-programs/5-year-integrated-teacher-education-track/',
'Accounting - Public Accountancy Major (CPA-150 track)'=>'/undergraduate/accounting/accounting-public-accountancy-major-cpa-150-track/',
'Accounting - Public Accounting Major (120 credits)'=>'/undergraduate/accounting/accounting-public-accounting-major-120-credits/',
'Accounting Advisory (Adv Cert)'=>'/gabelli-graduate/certificate/accounting-advisory/',
'Accounting Concentration'=>'/gabelli-graduate/mba/concentrations/accounting/',
'Accounting Minor'=>'/undergraduate/accounting/accounting-minor/',
'Accounting Technology Analytics (Adv Cert)'=>'/gabelli-graduate/certificate/accounting-technology-analytics/',
'Accounting/Information Systems Major'=>'/undergraduate/information-systems/accounting-information-systems-major/',
'Accounting/Information Systems Major'=>'/undergraduate/accounting/accounting-information-systems-major/',
'Administration and Supervision (M.S.E.) for Catholic/Faith-Based Educational Leadership'=>'/gse/leadership/administration-supervision-catholic-faith-based-educational-leadership-mse/',
'Administration and Supervision (Ph.D.)'=>'/gse/leadership/administration-supervision-phd/',
'Adolescence Biology (M.S.T.)'=>'/gse/initial-teaching-mst/adolescence-biology/',
'Adolescence Chemistry (M.S.T.)'=>'/gse/initial-teaching-mst/adolescence-chemistry/',
'Adolescence Earth Science (M.S.T.)'=>'/gse/initial-teaching-mst/adolescence-earth-science/',
'Adolescence English Language Arts (M.S.T.)'=>'/gse/initial-teaching-mst/adolescence-english-language-arts/',
'Adolescence Mathematics (M.S.T.)'=>'/gse/initial-teaching-mst/adolescence-mathematics/',
'Adolescence Physics (M.S.T.)'=>'/gse/initial-teaching-mst/adolescence-physics/',
'Adolescence Social Studies (M.S.T.)'=>'/gse/initial-teaching-mst/adolescence-social-studies/',
'Adolescence Special Education (M.S.T.)'=>'/gse/initial-teaching-mst/adolescence-special-education/',
'Adult Faith Formation (Adv Cert)'=>'/gre/certificate/faith-formation/',
'African and African American Studies Major'=>'/undergraduate/african-african-american-studies/major/',
'African and African American Studies Minor'=>'/undergraduate/african-african-american-studies/minor/',
'African Studies Minor'=>'/undergraduate/african-studies/minor/',
'American Catholic Studies Certificate'=>'/undergraduate/american-catholic-studies/certificate/',
'American Studies Major'=>'/undergraduate/american-studies/major/',
'American Studies Minor'=>'/undergraduate/american-studies/minor/',
'Anthropology Major'=>'/undergraduate/anthropology/major/',
'Anthropology Minor'=>'/undergraduate/anthropology/minor/',
'Applied Accounting and Finance Major'=>'/undergraduate/finance/applied-accounting-finance-major/',
'Applied Accounting and Finance Major'=>'/undergraduate/accounting/applied-accounting-finance-major/',
'Applied Developmental Psychology (Ph.D.)'=>'/gsas/psychology/applied-developmental-phd/',
'Applied Psychological Methods (M.S.)'=>'/gsas/psychology/applied-methods-ms/',
'Applied Statistics and Decision Making (M.S.)'=>'/gabelli-graduate/ms/applied-statistics-decision-making/',
'Arabic Minor'=>'/undergraduate/modern-languages-literatures/arabic-minor/',
'Art History and Visual Arts Double Major'=>'/undergraduate/theatre-visual-arts/art-history-visual-arts-double-major/',
'Art History and Visual Arts Double Major'=>'/undergraduate/art-history/art-history-visual-arts-double-major/',
'Art History Major'=>'/undergraduate/art-history/major/',
'Art History Minor'=>'/undergraduate/art-history/minor/',
'Auditing and Assurance (Adv Cert)'=>'/gabelli-graduate/certificate/auditing-assurance/',
'B.A. in Social Work'=>'/gss/basw/',
'Bilingual Childhood Education (M.S.T.)'=>'/gse/initial-teaching-mst/bilingual-childhood/',
'Bilingual Education, Advanced Certificate'=>'/gse/adv-certificate/bilingual/',
'Bilingual School Psychology, Advanced Certificate'=>'/gse/school-psychology/bilingual-school-psychology-professional-diploma/',
'Bilingual Special Education, Advanced Certificate'=>'/gse/adv-certificate/bilingual-special-education/',
'Bioethics Minor'=>'/undergraduate/bioethics/minor/',
'Bioinformatics Minor'=>'/undergraduate/computer-information-sciences/bioinformatics-minor/',
'Biological Sciences (M.S.)'=>'/gsas/biological-sciences/ms/',
'Biological Sciences (Ph.D.)'=>'/gsas/biological-sciences/phd/',
'Biological Sciences Major'=>'/undergraduate/biological-sciences/major/',
'Biological Sciences Minor'=>'/undergraduate/biological-sciences/minor/',
'Blockchain Secondary Concentration'=>'/gabelli-graduate/mba/concentrations/blockchain/',
'Business Administration Major'=>'/undergraduate/business-administration/business-administration-major/',
'Business Administration Minor'=>'/undergraduate/business-administration/business-administration-minor/',
'Business Analytics (M.S.)'=>'/gabelli-graduate/ms/business-analytics/',
'Business Law and Ethics Minor'=>'/undergraduate/business-law-ethics/business-law-ethics-minor/',
'Business Major'=>'/undergraduate/pcs-programs/business-major/',
'Catholic Theology (M.A.)'=>'/gsas/theology/catholic-ma/',
'Chemistry Major'=>'/undergraduate/chemistry/major/',
'Chemistry Minor'=>'/undergraduate/chemistry/minor/',
'Childhood Education (M.S.T.)'=>'/gse/initial-teaching-mst/childhood/',
'Childhood Special Education (M.S.E.)'=>'/gse/advanced-specialist-teaching-mse/childhood-special-education/',
'Childhood Special Education (M.S.T.)'=>'/gse/initial-teaching-mst/childhood-special-education/',
'Childhood Special Education, Advanced Certificate'=>'/gse/adv-certificate/childhood-special-education/',
'Christian Spirituality (Adv Cert)'=>'/gre/certificate/christian-spirituality/',
'Christian Spirituality (M.A.)'=>'/gre/masters/christian-spirituality/',
'Classical Civilization Major'=>'/undergraduate/classical-languages-civilization/classical-civilization-major/',
'Classical Civilization Minor'=>'/undergraduate/classical-languages-civilization/classical-civilization-minor/',
'Classical Languages Major'=>'/undergraduate/classical-languages-civilization/classical-languages-major/',
'Classical Languages Minor'=>'/undergraduate/classical-languages-civilization/classical-languages-minor/',
'Classics (M.A.)'=>'/gsas/classical-languages-civilization/ma/',
'Classics (Ph.D.)'=>'/gsas/classical-languages-civilization/phd/',
'Clinical Psychology (Ph.D.)'=>'/gsas/psychology/clinical-phd/',
'Clinical Research Methodology (M.S.)'=>'/gsas/psychology/clinical-research-ms/',
'College at Sixty'=>'/undergraduate/pcs-programs/college-at-sixty/',
'Communication and Culture Major'=>'/undergraduate/communication-media-studies/communication-culture-major/',
'Communication and Culture Minor'=>'/undergraduate/communication-media-studies/communication-culture-minor/',
'Communication and Media Management Concentration'=>'/gabelli-graduate/mba/concentrations/communications-media/',
'Communications Major'=>'/undergraduate/pcs-programs/communications-major/',
'Communications Minor'=>'/undergraduate/pcs-programs/communications-minor/',
'Comparative Literature Major'=>'/undergraduate/comparative-literature/major/',
'Comparative Literature Minor'=>'/undergraduate/comparative-literature/minor/',
'Computer Science (M.S.)'=>'/gsas/computer-information-sciences/ms/',
'Computer Science Major'=>'/undergraduate/computer-information-sciences/computer-science-major/',
'Computer Science Minor'=>'/undergraduate/computer-information-sciences/computer-science-minor/',
'Concentration in Accounting'=>'/undergraduate/accounting/concentration-accounting/',
'Concentration in Alternative Investments'=>'/undergraduate/finance/concentration-alternative-investments/',
'Concentration in Business Economics'=>'/undergraduate/business-economics/concentration-business-economics/',
'Concentration in Business Law and Ethics'=>'/undergraduate/business-law-ethics/concentration-business-law-ethics/',
'Concentration in Business of Healthcare'=>'/undergraduate/strategy/concentration-business-healthcare/',
'Concentration in Communications and Media Management'=>'/undergraduate/communications-media-management/concentration-communications-media-management/',
'Concentration in Consulting'=>'/undergraduate/strategy/concentration-consulting/',
'Concentration in Digital Media and Technology'=>'/undergraduate/digital-media-technology/concentration-digital-media-technology/',
'Concentration in Entrepreneurship'=>'/undergraduate/leading-people-organizations/concentration-entrepreneurship/',
'Concentration in Entrepreneurship'=>'/undergraduate/strategy/concentration-entrepreneurship/',
'Concentration in Finance'=>'/undergraduate/finance/concentration-finance/',
'Concentration in Fintech'=>'/undergraduate/finance/concentration-fintech/',
'Concentration in Global Business'=>'/undergraduate/global-business/concentration-global-business/',
'Concentration in Global Finance and Business Economics'=>'/undergraduate/finance/concentration-global-finance-business-economics/',
'Concentration in Global Marketing and Consumer Insights'=>'/undergraduate/marketing/concentration-global-marketing-consumer-insights/',
'Concentration in Healthcare Management'=>'/undergraduate/strategy/concentration-healthcare-management/',
'Concentration in Information Systems'=>'/undergraduate/information-systems/concentration-information-systems/',
'Concentration in Management'=>'/undergraduate/leading-people-organizations/concentration-management/',
'Concentration in Management'=>'/undergraduate/operations/concentration-management/',
'Concentration in Management'=>'/undergraduate/strategy/concentration-management/',
'Concentration in Marketing'=>'/undergraduate/marketing/concentration-marketing/',
'Concentration in Marketing Analytics'=>'/undergraduate/marketing/concentration-marketing-analytics/',
'Concentration in Personal Development and Leadership'=>'/undergraduate/leading-people-organizations/concentration-personal-development-leadership/',
'Concentration in Process and Quality Analytics'=>'/undergraduate/operations/concentration-process-quality-analytics/',
'Concentration in Services Marketing'=>'/undergraduate/marketing/concentration-services-marketing/',
'Concentration in Sports Business'=>'/undergraduate/sports-business/concentration-sports-business/',
'Concentration in Strategic Branding'=>'/undergraduate/marketing/concentration-strategic-branding/',
'Concentration in Value Investing'=>'/undergraduate/finance/concentration-value-investing/',
'Conservation Biology (Adv Cert)'=>'/gsas/biological-sciences/conservation-cert/',
'Construction Management (Adv Cert)'=>'/pcs-grad/cert-construction-management/',
'Contemporary Learning and Interdisciplinary Research (Ph.D.)'=>'/gse/interdisciplinary-research/contemporary-learning-interdisciplinary-research-phd/',
'Cooperative Program in Engineering (3-2 Engineering)'=>'/undergraduate/special-academic-programs/cooperative-program-in-engineering/',
'Corporate Compliance Secondary Concentration'=>'/gabelli-graduate/mba/concentrations/corporate-compliance/',
'Corporate Taxation (Adv Cert)'=>'/gabelli-graduate/certificate/corporate-taxation/',
'Counseling Psychology (Ph.D.)'=>'/gse/counseling-psychology/counseling-psychology-phd/',
'Creative Writing Minor'=>'/undergraduate/english/creative-writing-minor/',
'Credit for Experiential Learning'=>'/undergraduate/pcs-programs/credit-for-experiential-learning/',
'Curriculum and Teaching (M.S.E.)'=>'/gse/non-certification/curriculum-and-teaching-mse/',
'Cybersecurity (M.S.)'=>'/gsas/computer-information-sciences/cybersecurity-ms/',
'Cybersecurity Minor'=>'/undergraduate/computer-information-sciences/cybersecurity-minor/',
'D.P.S. in Business'=>'/gabelli-graduate/doctoral/dps/',
'Dance Major'=>'/undergraduate/dance/major/',
'Data Analytics (M.S.)'=>'/gsas/computer-information-sciences/data-analytics-ms/',
'Digital and Social Media Professional Certificate Program'=>'/undergraduate/pcs-programs/digital-and-social-media-professional-certificate/',
'Digital Technologies and Emerging Media Major'=>'/undergraduate/communication-media-studies/digital-technologies-emerging-media-major/',
'Digital Technologies and Emerging Media Minor'=>'/undergraduate/communication-media-studies/digital-technologies-emerging-media-minor/',
'Disability Studies Minor'=>'/undergraduate/disability-studies/minor/',
'Dual Degree in Business Analytics (M.S.) and Information Technology (M.S.)'=>'/gabelli-graduate/ms/dual-degree-business-analytics-information-technology/',
'Early Childhood and Childhood Education (M.S.T.)'=>'/gse/initial-teaching-mst/early-childhood-childhood/',
'Early Childhood Education (M.S.T.)'=>'/gse/initial-teaching-mst/early-childhood/',
'Early Childhood Special Education (M.S.E.)'=>'/gse/advanced-specialist-teaching-mse/early-childhood-special-education/',
'Early Childhood Special Education (M.S.T.)'=>'/gse/initial-teaching-mst/early-childhood-special-dual-certification/',
'Early Childhood Special Education, Advanced Certificate'=>'/gse/adv-certificate/early-childhood-special-education/',
'Economics (M.A.)'=>'/gsas/economics/ma/',
'Economics (Ph.D.)'=>'/gsas/economics/phd/',
'Economics Major'=>'/undergraduate/economics/economics-major/',
'Economics Minor'=>'/undergraduate/economics/minor/',
'Educational Evaluation and Intervention (M.S.E)'=>'/gse/school-psychology/educational-evaluation-intervention-mse/',
'Educational Leadership, Administration, and Policy (Ed.D.)'=>'/gse/leadership/administration-supervision-edd/',
'Elections and Campaign Management (M.A.)'=>'/gsas/political-science/elections-campaign-ma/',
'Electronic Business Secondary Concentration'=>'/gabelli-graduate/mba/concentrations/electronic-business/',
'Emerging Markets and Country Risk Analysis (Adv Cert)'=>'/gsas/iped/emerging-markets-cert/',
'Engineering Physics Major'=>'/undergraduate/physics-engineering/engineering-physics-major/',
'Engineering Physics Minor'=>'/undergraduate/physics-engineering/engineering-physics-minor/',
'English (M.A.)'=>'/gsas/english/ma/',
'English (Ph.D.)'=>'/gsas/english/phd/',
'English as a World Language (M.S.)'=>'/gse/non-certification/english-as-world-language-ms/',
'English Major'=>'/undergraduate/english/major/',
'English Major with a Creative Writing Concentration'=>'/undergraduate/english/creative-writing-major/',
'English Minor'=>'/undergraduate/english/minor/',
'Entrepreneurship Secondary Concentration'=>'/gabelli-graduate/mba/concentrations/entrepreneurship/',
'Environmental Science Major'=>'/undergraduate/environmental-science/major/',
'Environmental Studies Major'=>'/undergraduate/environmental-studies/environmental-studies-major/',
'Environmental Studies Minor'=>'/undergraduate/environmental-studies/environmental-studies-minor/',
'Ethics and Society (M.A.)'=>'/gsas/ethics/society-ma/',
'Evaluation Methodology Advisory (Adv Cert)'=>'/gabelli-graduate/certificate/evaluation-methodology-advisory/',
'Exceptional Adolescents with Subject Extension (M.S.T.)'=>'/gse/initial-teaching-mst/exceptional-adolescents-subject-extension/',
'Exceptional Adolescents with Subject Extension, Advanced Certificate'=>'/gse/adv-certificate/exceptional-adolescents-subject-extension/',
'Executive M.B.A.'=>'/gabelli-graduate/mba/executive-mba/',
'Fashion Studies Minor'=>'/undergraduate/fashion-studies/minor/',
'Film and Television Major'=>'/undergraduate/communication-media-studies/film-television-major/',
'Film and Television Minor'=>'/undergraduate/communication-media-studies/film-television-minor/',
'Finance Concentration'=>'/gabelli-graduate/mba/concentrations/finance/',
'Finance Major'=>'/undergraduate/finance/finance-major/',
'Financial Computing (Adv Cert)'=>'/gabelli-graduate/certificate/financial-computing/',
'Financial Computing (Adv Cert)'=>'/gsas/computer-information-sciences/financial-computing-cert/',
'Financial Econometrics and Data Analysis (Adv Cert)'=>'/gsas/iped/econometrics-cert/',
'FinTech Concentration'=>'/gabelli-graduate/mba/concentrations/fintech/',
'Five-Year Teacher Education Program (B.A./B.S. and M.S.T.)'=>'/gse/initial-teaching-mst/five-year-program/',
'French Language and Literature Major'=>'/undergraduate/modern-languages-literatures/french-literature-major/',
'French Minor'=>'/undergraduate/modern-languages-literatures/french-minor/',
'French Studies Major'=>'/undergraduate/modern-languages-literatures/french-studies-major/',
'Full-Time Cohort M.B.A.'=>'/gabelli-graduate/mba/fulltime-mba/',
'General and Exceptional Adolescents (M.S.T.)'=>'/gse/initial-teaching-mst/general-and-exceptional-adolescents-dual-certification/',
'General Science Major'=>'/undergraduate/general-science/major/',
'German Language and Literature Major'=>'/undergraduate/modern-languages-literatures/german-literature-major/',
'German Minor'=>'/undergraduate/modern-languages-literatures/german-minor/',
'German Studies Major'=>'/undergraduate/modern-languages-literatures/german-studies-major/',
'Global Business'=>'/undergraduate/global-business/',
'Global Business Major'=>'/undergraduate/global-business/major/',
'Global Finance (M.S.)'=>'/gabelli-graduate/ms/global-finance/',
'Global History (M.A.)'=>'/gsas/history/global-ma/',
'Global Sustainability Secondary Concentration'=>'/gabelli-graduate/mba/concentrations/global-sustainability/',
'GSAS Accelerated Master\'s Programs'=>'/undergraduate/special-academic-programs/early-masters-five-year-programs/',
'Health Administration (M.S.)'=>'/gabelli-graduate/ms/health-administration/',
'Health Administration (M.S.)'=>'/gsas/health-administration/ms/',
'Health Care Ethics (Adv Cert)'=>'/gsas/ethics/health-care-ethics-cert/',
'Healthcare Management Secondary Concentration'=>'/gabelli-graduate/mba/concentrations/healthcare-management/',
'History (M.A.)'=>'/gsas/history/ma/',
'History (Ph.D.)'=>'/gsas/history/phd/',
'History Major'=>'/undergraduate/history/major/',
'History Minor'=>'/undergraduate/history/minor/',
'Honors Program at FCLC'=>'/undergraduate/honors-program-fclc/',
'Honors Program at FCRH'=>'/undergraduate/honors-program-fcrh/',
'Honors Program at PCS'=>'/undergraduate/honors-program-pcs/',
'Humanitarian Studies (M.S.)'=>'/gsas/humanitarian-affairs/studies-ms/',
'Humanitarian Studies Major'=>'/undergraduate/humanitarian-studies/major/',
'Humanitarian Studies Minor'=>'/undergraduate/humanitarian-studies/minor/',
'Individual Wealth Management Taxation (Adv Cert)'=>'/gabelli-graduate/certificate/individual-wealth-management-taxation/',
'Individualized Major'=>'/undergraduate/individualized-major/',
'Information Science Major'=>'/undergraduate/computer-information-sciences/information-science-major/',
'Information Science Minor'=>'/undergraduate/computer-information-sciences/information-science-minor/',
'Information Systems Concentration'=>'/gabelli-graduate/mba/concentrations/information-systems/',
'Information Systems Major'=>'/undergraduate/information-systems/information-systems-major/',
'Information Technology (M.S.)'=>'/gabelli-graduate/ms/information-technology/',
'Information Technology and Systems Major'=>'/undergraduate/pcs-programs/information-technology-systems-major/',
'Integrative Neuroscience Major'=>'/undergraduate/integrative-neuroscience/major/',
'International Business Bridge'=>'/gabelli-graduate/preparatory/international-bridge/',
'International Business Secondary Concentration'=>'/gabelli-graduate/mba/concentrations/international-business/',
'International Diploma in Humanitarian Assistance (Adv Cert)'=>'/gsas/humanitarian-affairs/humanitarian-assistance-dipl/',
'International Diploma in Management of Humanitarian Action (Adv Cert)'=>'/gsas/humanitarian-affairs/management-humanitarian-action-dipl/',
'International Diploma in Operational Humanitarian Assistance (Adv Cert)'=>'/gsas/humanitarian-affairs/operational-humanitarian-assistance-dipl/',
'International Humanitarian Action (M.A.)'=>'/gsas/humanitarian-affairs/miha/',
'International Political Economy and Development (M.A.)'=>'/gsas/iped/ma/',
'International Political Economy Major'=>'/undergraduate/international-political-economy/major/',
'International Studies Major'=>'/undergraduate/international-studies/major/',
'Irish Studies Minor'=>'/undergraduate/irish-studies/minor/',
'Italian Language and Literature Major'=>'/undergraduate/modern-languages-literatures/italian-literature-major/',
'Italian Minor'=>'/undergraduate/modern-languages-literatures/italian-minor/',
'Italian Studies Major'=>'/undergraduate/modern-languages-literatures/italian-studies-major/',
'Jewish Studies Minor'=>'/undergraduate/jewish-studies/minor/',
'Journalism Major'=>'/undergraduate/communication-media-studies/journalism-major/',
'Journalism Minor'=>'/undergraduate/communication-media-studies/journalism-minor/',
'Latin American and Latino Studies Major'=>'/undergraduate/latin-american-latino-studies/major/',
'Latin American and Latino Studies Minor'=>'/undergraduate/latin-american-latino-studies/minor/',
'Latinx Ministry (Adv Cert)'=>'/gre/certificate/latinx-ministry/',
'Legal and Policy Studies Major'=>'/undergraduate/pcs-programs/legal-policy-studies-major/',
'Legal and Policy Studies Minor'=>'/undergraduate/pcs-programs/legal-policy-studies-minor/',
'Literacy Education, Birth-Grade 6 (M.S.E.)'=>'/gse/advanced-specialist-teaching-mse/literacy-education-b-6/',
'Literacy Education, Grades 5 through 12 (M.S.E.)'=>'/gse/advanced-specialist-teaching-mse/literacy-education-5-12/',
'Literacy Leadership, Advanced Certificate'=>'/gse/non-certification/literacy-leadership-adv-certificate/',
'M.S. in Nonprofit Leadership'=>'/gss/npl/',
'M.S.W. Dual Degree Programs'=>'/gss/msw/dual/',
'M.S.W. Hybrid'=>'/gss/msw/hybrid/',
'M.S.W. On Campus'=>'/gss/msw/on-campus/',
'M.S.W. Online'=>'/gss/msw/online/',
'Management (M.S., on campus and online)'=>'/gabelli-graduate/ms/management/',
'Management Concentration'=>'/gabelli-graduate/mba/concentrations/management/',
'Mandarin Chinese Minor'=>'/undergraduate/modern-languages-literatures/mandarin-chinese-minor/',
'Marketing Concentration'=>'/gabelli-graduate/mba/concentrations/marketing/',
'Marketing Intelligence (M.S.)'=>'/gabelli-graduate/ms/marketing-intelligence/',
'Marketing Major'=>'/undergraduate/marketing/marketing-major/',
'Marketing Minor'=>'/undergraduate/marketing/marketing-minor/',
'Mathematics and Computer & Information Sciences Major'=>'/undergraduate/computer-information-sciences/mathematics-computer-information-sciences-major/',
'Mathematics and Computer & Information Sciences Major'=>'/undergraduate/mathematics/mathematics-computer-information-sciences-major/',
'Mathematics Major'=>'/undergraduate/mathematics/major/',
'Mathematics Minor'=>'/undergraduate/mathematics/minor/',
'Mathematics/Economics Major'=>'/undergraduate/economics/mathematics-economics-major/',
'Mathematics/Economics Major'=>'/undergraduate/mathematics/mathematics-economics-major/',
'Media Management (M.S.)'=>'/gabelli-graduate/ms/media-management/',
'Medieval Studies (Adv Cert)'=>'/gsas/medieval-studies/cert/',
'Medieval Studies (M.A.)'=>'/gsas/medieval-studies/ma/',
'Medieval Studies Major'=>'/undergraduate/medieval-studies/major/',
'Medieval Studies Minor'=>'/undergraduate/medieval-studies/minor/',
'Mental Health Counseling (M.S.E.)'=>'/gse/counseling-psychology/mental-health-counseling-mse/',
'Middle Childhood Biology 7-9, Advanced Certificate'=>'/gse/adv-certificate/middle-childhood-biology-7-9/',
'Middle Childhood Chemistry 7-9, Advanced Certificate'=>'/gse/adv-certificate/middle-childhood-chemistry-7-9/',
'Middle Childhood English 7-9, Advanced Certificate'=>'/gse/adv-certificate/middle-childhood-english-7-9/',
'Middle Childhood Mathematics 7-9, Advanced Certificate'=>'/gse/adv-certificate/middle-childhood-mathematics-7-9/',
'Middle Childhood Physics 7-9, Advanced Certificate'=>'/gse/adv-certificate/middle-childhood-physics-7-9/',
'Middle Childhood Social Studies 7-9, Advanced Certificate'=>'/gse/adv-certificate/middle-childhood-social-studies-7-9/',
'Middle East Studies Major'=>'/undergraduate/middle-east-studies/major/',
'Middle East Studies Minor'=>'/undergraduate/middle-east-studies/minor/',
'Ministry (D.Min.)'=>'/gre/doctoral/ministry/',
'Music Major'=>'/undergraduate/music/major/',
'Music Minor'=>'/undergraduate/music/minor/',
'Natural Science Major'=>'/undergraduate/natural-sciences/major/',
'New Media and Digital Design Major'=>'/undergraduate/new-media-digital-design/major/',
'New Media and Digital Design Minor'=>'/undergraduate/new-media-digital-design/minor/',
'Organizational Leadership Major'=>'/undergraduate/pcs-programs/organizational-leadership-major/',
'Organizational Leadership Minor'=>'/undergraduate/pcs-programs/organizational-leadership-minor/',
'Orthodox Christian Studies Minor'=>'/undergraduate/orthodox-christian-studies/minor/',
'Pastoral Care (M.A.)'=>'/gre/masters/pastoral-care/',
'Pastoral Mental Health Counseling (M.A.)'=>'/gre/masters/pastoral-mental-health-counseling/',
'Pastoral Studies (M.A.)'=>'/gre/masters/pastoral-studies/',
'Peace and Justice Studies Minor'=>'/undergraduate/peace-justice-studies/minor/',
'Ph.D. in Business'=>'/gabelli-graduate/doctoral/phd/',
'Ph.D. in Social Work'=>'/gss/phd/',
'Philosophical Resources (M.A.)'=>'/gsas/philosophy/philosophical-resources-ma/',
'Philosophy (M.A.)'=>'/gsas/philosophy/ma/',
'Philosophy (Ph.D.)'=>'/gsas/philosophy/phd/',
'Philosophy Major'=>'/undergraduate/philosophy/major/',
'Philosophy Minor'=>'/undergraduate/philosophy/minor/',
'Physics Major'=>'/undergraduate/physics-engineering/physics-major/',
'Physics Minor'=>'/undergraduate/physics-engineering/physics-minor/',
'Playwriting (M.F.A.)'=>'/gsas/theatre/playwriting-mfa/',
'Political Science Major'=>'/undergraduate/political-science/major/',
'Political Science Minor'=>'/undergraduate/political-science/minor/',
'Post-Baccalaureate Pre-Medical/Pre-Health Program'=>'/undergraduate/pcs-programs/post-baccalaureate-pre-medical-pre-health-program/',
'Pre-Law'=>'/undergraduate/special-academic-programs/pre-law-program/',
'Pre-MBA Program'=>'/gabelli-graduate/preparatory/pre-mba/',
'Pre-Medical and Pre-Health'=>'/undergraduate/special-academic-programs/pre-medical-pre-health/',
'Preschool Child Psychology (M.S.E.)'=>'/gse/school-psychology/preschool-child-psychology-mse/',
'Professional Accounting (M.S.)'=>'/gabelli-graduate/ms/professional-accounting/',
'Professional M.B.A.'=>'/gabelli-graduate/mba/professional-mba/',
'Professional Taxation (M.S.)'=>'/gabelli-graduate/ms/professional-taxation/',
'Psychology Major'=>'/undergraduate/psychology/major/',
'Psychology Minor'=>'/undergraduate/psychology/minor/',
'Psychology of Bilingual Students (M.S.E.)'=>'/gse/school-psychology/psychology-bilingual-students-mse/',
'Psychometrics and Quantitative Psychology (Ph.D.)'=>'/gsas/psychology/psychometrics-quantitative-phd/',
'Public Accountancy Concentration'=>'/gabelli-graduate/mba/concentrations/public-accountancy/',
'Public Media (M.A.)'=>'/gsas/communication-media-studies/public-media-ma/',
'Public Opinion and Survey Research (Adv Cert)'=>'/gsas/political-science/public-opinion-survey-research-cert/',
'Quantitative Finance (M.S.)'=>'/gabelli-graduate/ms/quantitative-finance/',
'Real Estate (M.S.)'=>'/pcs-grad/ms-real-estate/',
'Real Estate Development (Adv Cert)'=>'/pcs-grad/cert-real-estate-development/',
'Real Estate Finance (Adv Cert)'=>'/pcs-grad/cert-real-estate-finance/',
'Real Estate Major'=>'/undergraduate/pcs-programs/real-estate-major/',
'Religious Education (M.A.)'=>'/gre/masters/religious-education/',
'Religious Education (Ph.D.)'=>'/gre/doctoral/religious-education/',
'Religious Studies Major'=>'/undergraduate/religious-studies/major/',
'Religious Studies Minor'=>'/undergraduate/religious-studies/minor/',
'Russian Minor'=>'/undergraduate/modern-languages-literatures/russian-minor/',
'School Building Leader (M.S.E.)'=>'/gse/leadership/school-building-leader-mse/',
'School Counseling (M.S.E.)'=>'/gse/counseling-psychology/school-counseling-mse/',
'School District Leadership, Advanced Certificate'=>'/gse/leadership/school-district-leadership-advanced-certificate/',
'School Psychology (Ph.D.)'=>'/gse/school-psychology/school-psychology-phd/',
'School Psychology Bilingual Extension, Adv Cert'=>'/gse/school-psychology/bilingual-school-psychology-advanced-certificate/',
'School Psychology, Advanced Certificate'=>'/gse/school-psychology/school-psychology-professional-diploma/',
'Social Work Major'=>'/undergraduate/social-work/major/',
'Sociology Major'=>'/undergraduate/sociology/major/',
'Sociology Minor'=>'/undergraduate/sociology/minor/',
'Spanish Language and Literature Major'=>'/undergraduate/modern-languages-literatures/spanish-language-literature-major/',
'Spanish Minor'=>'/undergraduate/modern-languages-literatures/spanish-minor/',
'Spanish Studies Major'=>'/undergraduate/modern-languages-literatures/spanish-studies-major/',
'Special Education/Teaching English to Speakers of Other Languages, Advanced Certificate'=>'/gse/adv-certificate/special-education-tesol/',
'Spiritual Direction (Adv Cert)'=>'/gre/certificate/spiritual-direction/',
'Sports Journalism Minor'=>'/undergraduate/communication-media-studies/sports-journalism-minor/',
'Strategic Marketing Communications (M.S. Online)'=>'/gabelli-graduate/ms/marketing-communications/',
'Teaching English to Speakers of Other Languages (M.S.T.)'=>'/gse/initial-teaching-mst/tesol/',
'Teaching English to Speakers of Other Languages, Advanced Certificate'=>'/gse/adv-certificate/tesol/',
'Theatre Major'=>'/undergraduate/theatre-visual-arts/theatre-major/',
'Theatre Minor'=>'/undergraduate/theatre-visual-arts/theatre-minor/',
'Theology (Ph.D.)'=>'/gsas/theology/phd/',
'Theology Minor'=>'/undergraduate/theology/minor/',
'Theology Religious Studies Major'=>'/undergraduate/theology/theology-religious-studies-major/',
'Theology Religious Studies Second Major'=>'/undergraduate/theology/theology-religious-studies-secondary-major/',
'Therapeutic Interventions (M.S.E.)'=>'/gse/school-psychology/therapeutic-interventions-mse/',
'Urban Studies (M.A.)'=>'/gsas/urban-studies/ma/',
'Urban Studies Major'=>'/undergraduate/urban-studies/major/',
'Urban Studies Minor'=>'/undergraduate/urban-studies/minor/',
'Visual Arts Major'=>'/undergraduate/theatre-visual-arts/visual-arts-major/',
'Visual Arts Minor'=>'/undergraduate/theatre-visual-arts/visual-arts-minor/',
'Women, Gender, and Sexuality Studies Major'=>'/undergraduate/women-gender-sexuality-studies/major/',
'Women, Gender, and Sexuality Studies Minor'=>'/undergraduate/women-gender-sexuality-studies/minor/');

$codes = array('CB', 'CB_L','CB_R','CE', 'CL', 'CP', 'CR', 'ES', 'FC', 'GA', 'GB', 'GC', 'GE', 'GL', 'GP', 'GR', 'GS', 'LD', 'PC', 'UC');
//$codes = array('CB','GA');

function getURL($index, $array) 
{ 
    if (array_key_exists($index, $array)){ 
        $url= "https://bulletin.fordham.edu".$array[$index]; 
    	//echo $index . " " . $url ."<br>";
	} 
    else{ 
        //echo $index ."<br>";; 
    } 
    return $url;
} 

function noURL($index, $array)
{
    if (!array_key_exists($index, $array)){
        $nourl= $index;
    }
    return $nourl;
}

  
$sd0 = '<?xml version="1.0"?>';
$sd1 = "\n";
$sd2 =  '<programinfo>';
$sd = $sd0 .$sd1 .$sd2 .$sd1;
//echo $sd;

for ( $y=0; $y<= sizeof($codes)-1; $y++ ){

	$codeurl = "https://bulletin.fordham.edu/ribbit/?page=getprogram.rjs&college=".$codes[$y];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $codeurl);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,  true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$xmlFC = curl_exec($ch);
curl_close($ch);

$xmlFC = new SimpleXMLElement($xmlFC);


for ( $i=0; $i<= sizeof($programurls)-1; $i++ ){
	$xmltitle = (string)$xmlFC->program[$i]->title;
             $cc  = (string)$xmlFC->program[$i]->college[code];

//echo $i ." " . getURL((string)$xmltitle,$programurls) ." " . $xmltitle ."<br>"; 

	if (array_key_exists( $xmltitle ,$programurls) ){
		$child = $xmlFC->program[$i]->addChild('url',getURL((string)$xmltitle,$programurls));

			$xmlFC_urls=$xmlFC->asXML(); 
		$xmlFC_urls = preg_replace('^\<\?xml version\=\"1\.0\"\?\>([\n+])^',"",$xmlFC_urls);
	    	$xmlFC_urls = preg_replace('^\<(/?)programinfo\>([\n+])^',"",$xmlFC_urls);
	}else{

		//echo $cc . " " .noURL((string)$xmltitle,$programurls) ."<br>";
	}
}
	if ( $y == 0 ) { $xmlFC_urls = $sd . $xmlFC_urls;}
              @file_put_contents("/var/www/jadu/public_html/site/custom_scripts/programs.xml", $xmlFC_urls,  FILE_APPEND);

echo $xmlFC_urls;
}


$myFile = "/var/www/jadu/public_html/site/custom_scripts/programs.xml";
$fh = fopen($myFile, 'a') or die("can't open file");
$sd2 = '</programinfo>';
fwrite($fh, $sd2."\n");
fclose($fh);

?>

</body>
</html>
