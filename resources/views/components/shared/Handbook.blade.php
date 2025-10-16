<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Handbook</title>
    <link rel="stylesheet" href="{{ asset('./vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('./css/handbook.css') }}">
</head>
<body>
        <!-- Dashboard-content -->
            <div class="d-flex align-items-center mb-4">
                <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <h3 class="mb-0">Dashboard</h3>
                <input type="text" class="form-control ms-auto w-25 w-md-50 w-sm-75" id="searchInput" placeholder="Search">
            </div>

            <!-- Student Handbook Card -->
            <div class="student-handbook-wrapper d-flex justify-content-center">
                <div class="card shadow student-handbook-container">
                    <div class="row h-100">
                        <!-- Left Side (Blue Panel) -->
                        <div class="col-md-4 handbook-sidebar text-center d-flex flex-column justify-content-center">
                            @if(Auth::user()->role === 'super')
                                <button class="add-btn btn btn-success" id="openModalBtn" title="Add New">
                                    <i class="bi bi-plus"></i>
                                </button>
                            @endif
                            <h2 class="handbook-title">Student<br>Handbook</h2>
                        </div>

                        <!-- Right Side (Scrollable Content) -->
                        <div class="col-md-8 handbook-content">
                            <header class="text-center mb-4">
                                <h3 class="section-title text-secondary fw-bold">STUDENT BEHAVIOR</h3>
                                <h2 class="section-subtitle fw-bold">& DISCIPLINE</h2>
                            </header>

                            <p>As part of the STI community, you are expected to act with maturity, integrity, and respect for people in 
                                authority, your fellow students, and the entire STI community. To ensure holistic development as an STI student, 
                                you are expected to observe the following guidelines:</p>

                            <section class="handbook-section-student-appearance">
                                <h4 class="fw-bold mt-4">Student Appearance</h4>
                                <p class="text-muted">Each student shall adhere to the conventions of good grooming as a sign of respect to oneself, others, and STI as an academic institution.</p>

                                <h5 class="sub-header">School Identification Card</h5>
                                <ol class="numbered-list">
                                    <li>An official school identification (ID) card shall be issued to bona fide STI students.</li>
                                    <li>The ID (including the official strap) is part of the uniform and must be worn properly and visibly at all times inside campus.</li>
                                    <li>The ID must be free from any alteration or modification.</li>
                                    <li>The ID is non-transferable. It must not be tampered with or misused.</li>
                                    <li>The ID is required for all official transactions and must be surrendered upon permanent exit from STI.</li>
                                    <li>Students must surrender damaged IDs and apply for replacement if unreadable or unusable.</li>
                                    <li>Lost IDs must be reported to the Registrar with an affidavit of loss for replacement.</li>
                                    <li>A temporary ID will be issued while the new one is processed.</li>
                                    <li>Giving false information about ID loss is a major offense.</li>
                                    <li>Only official or endorsed school uniforms are allowed for ID photo-taking.</li>
                                </ol>

                                <h5 class="sub-header mt-4">Procedure for ID Card Replacement</h5>
                                <ol class="numbered-list">
                                    <li>Secure a temporary gate pass from the guard for lost IDs.</li>
                                    <li>Get and fill out the Application for ID Replacement from the Registrar.</li>
                                    <li>Submit the form with a notarized affidavit of loss/damaged ID to the Registrar.</li>
                                    <li>Pay the replacement fee at the Cashier.</li>
                                    <li>Present the receipt to the Registrar to receive a temporary ID.</li>
                                </ol>
                            </section>

                            <section class="handbook-section-student-uniform">
                                <h4 class="fw-bold mt-4">Student Uniform</h4>
                                <p class="text-muted">Certain programs, courses, or activities require a different set of uniforms. Only STI issued or endorsed uniforms are allowed. 
                                    <br>For Physical Education (PE) classes, the prescribed shirt should be worn together with jogging pants, rubber shoes, and sports socks. 
                                    <br><b>Note:</b> The proper cut and size for uniforms should be observed. Skirt hemlines should not be higher than three (3) inches from the knee and slits should not reach the upper thighs.</p>
                                <h5 class="sub-header">Wash Day</h5>
                                <p>Wash days are specific days wherein students are allowed to wear STI proware shirts instead of the school uniform. 
                                    Students are not permitted to wear clothes that will offend or scandalize the sensibilities of the academic 
                                    community such as, but not limited to the following:</p>
                                <ul class="bulletin-list">
                                    <li>Shorts, miniskirts, low riding pants, ripped jeans/pants with slips, rips, or holes higher than 7 inches above the knee</li>
                                    <li>Outfits or accessories with offensive image/s or language such as the promotion of drugs, tobacco, alcohol, glorification of death and mutilation, statement or implication of profanity, sexual or pornographic activity</li>
                                    <li>Blouses or dress with plunging necklines, see-through, backless, strapless, body huggers, and skin-tight outfits</li>
                                    <li>Midriffs, halter or crop tops, sando/tank tops or sleeveless, and tube-type shirts and blouses</li>
                                    <li>Skirt hemlines should not be higher than three (3) inches from the knee and slits should not reach the upper thighs</li>
                                    <li>Use of wooden clogs, rubber or plastic slippers, and open-toe footwear</li>
                                </ul>
                                <p>Clothing must always be neat, clean, and worn as traditionally intended.</p>
                                <p>Students may opt to wear uniforms or wash day clothes in accordance with their gender identity but would need to follow the set guidelines. They may consult their Discipline Officer or School Administrator for the process. Once confirmed, the student is expected to continue wearing their gender-affirming clothing throughout their stay with the institution.</p>

                                <h5 class="sub-header">Grooming and Haircut</h5>
                                <ul class="bulletin-list">
                                    <li>Hair must be kept neat, clean, and well-groomed.</li>
                                    <li>Colored hair is allowed.</li>
                                    <li>Makeup must be light and natural-looking.</li>
                                    <li>Wearing of items with offensive image/s or language, statement or implication of profanity, sexual or pornographic activity deemed by the school to be dangerous or a distraction to the learning environment is not acceptable.</li>
                                    <li>Sunglasses, bandannas, or caps are not to be worn indoors.</li>
                                    <li>Attire, which may be used as a weapon, should not be worn (i.e., steel-toed boots, chains, "dog collars," or any items with spikes or studs).</li>
                                </ul>
                                <p>Specific programs, courses, or activities may require specific mandates for student appearance. For such, notices shall be provided by STI accordingly.</p>
                            </section>

                            <section class="handbook-section-student-decorum">
                                <h4 class="fw-bold mt-4">Student Decorum</h4>
                                <p class="text-muted">STI is not only concerned with the academic development of its students, but also with their character formation. Every STI student is expected to be refined in thoughts, words, and actions. An STI student should:</p>
                                <ol type="a" class="bulletin-list">
                                    <li>Uphold the academic integrity of the school, endeavor to achieve academic excellence, and abide by the rules and regulations governing academic responsibilities and moral integrity. Thus, in submitting any academic work, students are expected to properly cite references, direct quotes, and other sources including, but not limited to data obtained from the tables, illustrations, figures, pictures, images, and videos following the prescribed format of the discipline (i.e., American Psychological Association, Modern Language Association). This also applies to:</li>
                                    <ul class="bulletin-list">
                                        <li>previous works submitted to other courses that are results of collaborative or group effort</li>
                                        <li>computer codes written to accomplish a task or any activities required in any programming courses.</li>
                                    </ul>
                                    <li>Observe the usual norms of courtesy and etiquette in all areas of interpersonal relationships. Any act to the contrary, including unfavorable or offensive remarks about other persons regardless of their sex, creed, race, status, and political affiliation may be deemed prejudicial to the enrollment of the students concerned or to their status as alumni.</li>
                                    <li>Strive for student development by joining and actively participating in various activities sponsored by the school and recognized student organizations. It is strictly prohibited to form or be a member of an organization/fraternity/sorority that or is known to advocate, tolerate, or engage in violence or immoral behavior.</li>
                                    <li>Strictly observe classroom, laboratory, library, and other school offices procedures.</li>
                                    <li>Refrain from exhibiting boisterous conduct, such as loitering, whistling, loud talking, or any other action that may distract others from their studies.</li>
                                    <li>Strive to develop healthy interaction with other students. However, acts or gestures which tend to offend other members of the community, including public display of physical intimacy of the opposite or same sex, are not tolerated.</li>
                                    <li>Inform their parent or guardian of the following:</li>
                                    <ul class="bulletin-list">
                                        <li>Rules and regulations expressed in this handbook</li>
                                        <li>Their academic standing and the possible consequences of their excessive absences, dropping, failures, or gross misconduct</li>
                                    </ul>
                                </ol>

                                <h5 class="sub-header">Anti-Bullying & Anti-Cyberbullying Law Policy</h5>
                                <p>STI is committed to providing a healthy learning environment where students support and respect each other. Thus, within the school, it is made clear that bullying will not be tolerated. "Bullying shall refer to any severe or repeated use by one (1) or more students of a written, verbal, or electronic expression, or a physical act or gesture, or any combination thereof, directed at another student that has the effect of actually causing or placing the latter in reasonable fear of physical or emotional harm or damage to their property; creating a hostile environment at school for the other students, infringing on the rights of the other student at school; or materially and substantially disrupting the education process or the orderly operation of a school." (Republic Act No. 10627, "Anti-Bullying Act of 2013").</p>
                                <p>Due to the advancement in technology and social media, emphasis is given on the prevention of bullying in its electronic expression: Cyberbullying. Cyberbullying shall refer to acts of cruelty committed using the internet or any form of electronic media or technology that has the effect of stripping one's dignity or causing reasonable fear of physical or emotional harm.</p>
                                <p>Strategies and mechanisms against bullying and cyberbullying (e.g., conducting anti- bullying/cyberbullying orientations to students and personnel, academic and discipline policies, guidance and counseling, information dissemination through student-teacher- parent leaflets, etc.) are meant to increase awareness and address the unacceptable nature of bullying in and around the school.</p>
                                <p>Bullying and cyberbullying behavior are confronted clearly and pursued beyond the mere application of sanctions. Students who persist in bullying/cyberbullying, despite counseling and support, are given sanctions based on this handbook. Sanctions imposed will take into account the severity of the bullying/cyberbullying incident.</p>
                            
                                <h5 class="sub-header">Anti-Hazing Law Policy</h5>
                                <p>STI is committed to ensuring a peaceful environment where camaraderie among students is fostered through various interest groups or clubs inside the campus. Every student organization is prohibited from using any form of violence, force, threat, or intimidation as a prerequisite for admission. Any STI student who is found to have committed or has conspired to commit the aforementioned shall be subject to Republic Act No. 11053, otherwise known "Anti-Hazing Act of 2018," and appropriate disciplinary action provided in this handbook.</p>
                            
                                <h5 class="sub-header">Anti-Sexual Harassment Policy</h5>
                                <p>STI is committed to creating and maintaining an environment where all members of the STI community are free to study without fear of harassment of a sexual nature. STI adheres to Republic Act 7877, otherwise known as the "Anti-Sexual Harassment Act of 1995," which considers all forms of sexual harassment in the employment, education, or training unlawful and contrary to the dignity of every individual, as well as the latter's guarantee to respect of human rights. Given the seriousness of this matter, STI promulgates appropriate rules and regulations defining the offense of sexual harassment and outlining the procedure in the investigation and imposition of administrative sanctions in such cases.</p>
                            
                                <h5 class="sub-header">Gender and Development Policy</h5>
                                <p>STI recognizes gender sensitivity as it pertains to one's effort to show how gender shapes the role of women and men in society, including their role in the development and how their relationship affects each other. In support of the CHED Memorandum Order 01 series of 2015 entitled "Establishing the Policies and Guidelines on Gender and Development in the Commission on Higher Education and Higher Education Institutes (HEIs),"STI promotes gender awareness by appointing Gender and Development focal persons in each school to pursue and implement programs, projects, and activities that will contribute to the achievement of women's empowerment and gender equality. It shall also adopt gender mainstreaming in the academe as one of the strategies in educating and informing various sectors of society on the need to recognize and respect the rights of women and men.</p>
                            
                                <h5 class="sub-header">Smoking, Vaping, Prohibited Drugs, Paraphernalia or Illegal Substances, and Dangerous Weapons</h5>
                                <p>STI is committed to maintaining and sustaining a safe, healthy, and conducive learning environment for its students that should be entirely free from smoking, prohibited drugs, paraphernalia, and illegal substances, as well as deadly weapons or dangerous materials or instruments.</p>
                                <p>To ensure that this is achieved, the following measures shall be observed:</p>
                                <ol class="numbered-list">
                                    <li>Conduct student orientation, counseling, and mentoring to students on the negative effects of cigarette smoking/vaping, prohibited drugs, carrying deadly/ dangerous weapons or materials/instruments;</li>
                                    <li>Engage students in meaningful programs and activities that promote their welfare and development;</li>
                                    <li>Conduct random drug tests for students every term in accordance with the provisions of Republic Act 9165, otherwise known as the "Comprehensive Dangerous Drugs Act of 2002";</li>
                                    <li>Conduct bag inspection of those coming in and out of the school premises; and</li>
                                    <li>Ban smoking, sale, or distribution of e-cigarette or tobacco products in compliance with the provisions of Republic Act 9211, otherwise known as the "Tobacco Regulation Act of 2003" and (Executive Order No. 26, Series of 2017, "Providing for the Establishment of Smoke-Free Environment in Public and Enclosed Places.")</li>
                                </ol>

                                <h5 class="sub-header">Random Drug Testing</h5>
                                <p>With its commitment to provide optimum value to its stakeholders and to ensure that the students are free from the use of dangerous drugs, STI complies with the provisions in Republic Act No. 9165, otherwise known as the "Comprehensive Dangerous Drugs Act of 2002" and its Implementing Rules and Regulations, the Dangerous Drugs Board Regulation No. 6, series of 2003, as amended by Dangerous Drugs Board Regulation No. 3, series of 2009, and CHED Memorandum Order no. 18 series of 2018 Implementing Guidelines for the Conduct of Drug Testing in all Higher Education Institutions (HEI's). This aims to:</p>
                                <ul class="bulletin-list">
                                    <li>Deter students from using prohibited drugs and other illegal substances;</li>
                                    <li>Determine the occurrence of drug users among the students; and</li>
                                    <li>Facilitate the treatment and rehabilitation of confirmed drug users or dependents.</li>
                                </ul>
                                <p>All students enrolled are subject to random drug testing without their necessary concurrence and knowledge. The results of the tests are kept confidential and for the evaluation of the school only.</p>
                                <p>Students who are found to be positive for drug use after the confirmatory analysis will be informed of their test results with utmost secrecy and confidentiality. The parents/ guardians of the "confirmed positive" students will be informed and required to attend a scheduled case conference. No "confirmed positive" student shall be grounded for expulsion or given any disciplinary action and should not be reflected in any and all academic records but they are required to undergo an intervention program under the supervision of a Department of Health (DOH)-accredited facility or physician, private practitioners, or social worker, in consultation with parents/guardians.</p>
                                <p>However, a student who has undergone an intervention program but was found to be "confirmed positive" for the second time shall be sanctioned with either non-readmission or expulsion in accordance with the STI Drug Testing Policy.</p>

                                <h5 class="sub-header">Use of School Facilities</h5>
                                <p>Students should strive to keep their classrooms clean, pleasant, and conducive to learning. Chairs and tables must be aligned at all times. Lights, electric fans, and air conditioners should be turned off whenever the students leave the room or if not in use.</p>
                                <p>Students are also encouraged to keep the school building, study areas, and areas within the school property clean.</p>
                                <p>In any incident of destruction, damaging, tampering, or losing of school property, the school reserves the right to charge to the concerned student/s the cost of damage, including labor or repair.</p>

                                <h5 class="sub-header">Electronic Gadget Rule</h5>
                                <p>STI allows the students to bring and use laptops and other electronic gadgets relevant to learning inside the campus. However, using electronic gadgets for non-educational purposes during class hours is strictly prohibited. Gadgets must be turned off or put in a silent mode to prevent class disruption unless advised to use them for educational purposes. Proper etiquette in using various electronic gadgets shall also be carried out. Students may use their mobile phones for urgent purposes and during break time.</p>
                                <p>Safekeeping of gadgets brought to school is the student owner's responsibility. The school will not be liable for any loss or damage to electronic gadgets.</p>

                                <h5 class="sub-header">Social Media Policy</h5>
                                <p>STI is dedicated to nurturing an environment of mutual respect wherein members of its community are engaged in positive and responsible online behavior. Students and other members of the STI community are expected to be cautious when engaging in any action on social media that may impact the privacy, dignity, or rights of the school, groups, or individuals, including themselves. This shall be accomplished by:</p>
                                <ol class="numbered-list">
                                    <li>Reflecting on the potential impact of the content to be shared or posted to themselves or to others</li>
                                    <li>Maintaining appropriate boundaries when interacting with school personnel on social media</li>
                                    <li>Adhering to intellectual property rights</li>
                                    <li>Ensuring that when representing the school, posted content is aligned with the school's values and policies</li>
                                </ol>
                                <p>STI has the right to request the removal of any content that may risk the reputation of the school or a member of its community from a social media account.</p>

                                <h5 class="sub-header">Data Privacy Policy</h5>
                                <p>In accordance with the Data Privacy Act of 2012 (RA 10173), STI is committed to ensuring the confidentiality and security of information provided to the schools. General provisions on how the institutions use, store, and retain collected information can be accessed via https://www.sti.edu/dataprivacy.asp.</p>
                                <p>To help keep confidential details secure, students and other members of the STI community should observe the following:</p>
                                <ol class="numbered-list">
                                    <li>Refrain from sharing sensitive or confidential information</li>
                                    <li>Review privacy settings in social media and other platforms regularly</li>
                                    <li>Ensure that all devices are locked if not in use</li>
                                    <li>Check the security of the platform before opening them</li>
                                    <li>Avoid logging to personal accounts on free or public Wi-Fi</li>
                                </ol>
                            </section>

                            <section class="handbook-section-student-discipline">
                                <h4 class="fw-bold mt-4">Student Discipline</h4>
                                <h5 class="sub-header">Discipline Committee</h5>
                                <p>The Discipline Committee has jurisdiction over all cases involving student discipline and the imposition of sanctions. The committee's tasks revolve around investigating cases involving student discipline, where recommendations have to be given at the end of the investigation. It shall be composed of the following:</p>
                                <ol class="numbered-list">
                                    <li>Academic Head as Ex Officio Chairman. If unavailable, the Academic Head shall assign the Program Head to the role.</li>
                                    <li>Two (2) Faculty Representatives to be selected by the Academic Head</li>
                                    <li>Staff Representative to be appointed by the School Administrator/Deputy School Administrator</li>
                                    <li>A Representative from the Commission on Higher Education (CHED) or Technical Education and Skills Development Authority (TESDA), if available</li>
                                </ol>
                                <p>If any of the above is a respondent or involved in the dispute, another official representative shall be designated.</p>

                                <h5 class="sub-header">Initial Statement</h5>
                                <p>The Academic Head, Program Head/s, and the Discipline Officer shall have joint and equal authority or control over all student disputes requiring mediation. However, the Discipline Committee shall be convened to hear complaints or disputes or both that involve the imposition of disciplinary measures.</p>

                                <h5 class="sub-header">Implementation of Disciplinary Sanctions</h5>
                                <p>To help ensure an atmosphere conducive to learning, a special mechanism shall be established to administer appropriate and reasonable sanctions to erring members of the school community subject to the requirements of due process, as well as to resolve disputes among and between them.To help ensure an atmosphere conducive to learning, a special mechanism shall be established to administer appropriate and reasonable sanctions to erring members of the school community subject to the requirements of due process, as well as to resolve disputes among and between them.</p>

                                <h5 class="sub-header">Student Complaints</h5>
                                <p>Student complaints may be filed in writing with the Discipline Committee headed by the Academic Head. When applicable, an amicable settlement between the Complainant and the Respondent may be initially pursued.</p>
                                <p>The Discipline Committee (or the Academic Head) may, on its own initiative, take notice of any breach of discipline or rule involving students even without a complaint.</p>

                                <h5 class="sub-header">Procedure for Disciplinary Cases</h5>
                                <p>Due process is observed for cases that need to be investigated and may result in possible dismissal.</p>
                                <ol class="numbered-list">
                                    <li>The Complainant shall submit a written complaint to the Discipline Officer. If there is no assigned Discipline Officer, it should be submitted to the Academic Head, the Ex Officio Chairman of the Discipline Committee. When applicable, an amicable settlement between the Complainant and the Respondent may be initially pursued.</li>
                                    <li>The Discipline Officer or Academic Head shall set a meeting with the Complainant for consultation and discussion of their rights and possible consequences of pursuing the complaint.</li>
                                    <li>If the Complainant decides to pursue the case, the written complaint shall be forwarded to the members of the Discipline Committee for a resolution not later than 30 working days after its receipt of the complaint.</li>
                                    <li>The Respondent shall be notified in writing of the complaint filed against them which shall contain the nature and cause of the accusation against them. The notification shall, in all cases, direct the respondent to answer the accusation within three (3) working days from receipt. Failure to do so within the prescribed period shall not delay the proceedings.</li>
                                    <li>For student respondents who are minors, the parents or guardian shall likewise be notified in writing of the cause and accusation leveled against the Respondent.</li>
                                    <li>The Respondent shall be advised by the Head of the Discipline Committee of their rights and of the procedure to be followed in the proceedings resolving their case.</li>
                                    <li>Prior to the hearing, the Discipline Committee must refer the Complainant and the Respondent to the Guidance Counselor and schedule a separate one-on-one session for behavioral, emotional, and welfare purposes.</li>
                                    <li>The Discipline Committee may schedule hearings for the reception of evidence to enable it to arrive at a proper resolution of the complaint. In the case of a hearing wherein the Respondent is summoned by the Committee, the notice of hearing shall be given to the Respondent at least five (5) working days before the scheduled hearing. The Respondent shall be allowed to present evidence on their behalf.</li>
                                    <li>Taking into consideration all the evidence gathered during the proceedings, the Discipline Committee shall draft a resolution with a finding as to the liability of the Respondent. The resolution shall also contain a recommendation to the President/School Administrator/Deputy School Administrator as to the imposition of any penalty whenever applicable. The resolution shall be submitted to the President/School Administrator not later than 30 working days from the close of reception of evidence before the Committee.</li>
                                    <li>Upon receipt of the resolution of the Discipline Committee, the President/School Administrator/Deputy School Administrator shall make a decision as to whether or not to impose sanctions upon the Respondent. The decision shall be in writing and the same shall be served upon the Respondent. In the case of a Respondent being a minor, the parents or guardian of said Respondent shall also be served with the same. A copy of the resolution addressed to the Complainant should also be provided.</li>
                                    <li>An appeal to the President/School Administrator/Deputy School Administrator's decision may be made within 10 working days from receipt of the said decision by an appealing party. The appeal may come from either the Respondent or the Complainant and shall be addressed to the Office of the President/School Administrator.</li>
                                    <li>The School Administration reserves the right to place a Respondent under suspension pending appeal.</li>
                                    <li>The decision of the Discipline Committee shall be final and executory if not appealed within the given period.</li>
                                    <li>The Discipline Committee shall give a copy of the final resolution along with a Referral Form to the Guidance Office prior to the imposition of any disciplinary action or sanction.</li>
                                    <li>The Discipline Committee shall call for a conference with the Respondent and their parents or guardian and a separate conference with the Complainant and their parents or guardian to discuss the final resolution. Both parties shall be provided with their own copy of the final resolution.</li>
                                </ol>

                                <h5 class="sub-header">The Procedure of the Guidance and Counseling Office in Handling Discipline Cases Referred by the Discipline Committee</h5>
                                <ol class="numbered-list">
                                    <li>The Guidance Counselor shall receive a Referral Form from the Discipline Committee with a copy of the Incident Report.</li>
                                    <li>The Guidance Counselor will conduct a conference with the student/s concerned.</li>
                                    <li>The Guidance Counselor will provide the Discipline Committee with initial feedback.</li>
                                    <li>The Guidance Counselor will conduct a follow-up conference with the student/s after the decision/resolution of the Discipline Committee has been made.</li>
                                    <li>The Guidance Counselor will provide the Discipline Committee with feedback and a follow-up plan of action for the student/s.</li>
                                </ol>
                            </section>

                            <section class="handbook-section-disciplinary-measures">
                                <h4 class="fw-bold mt-4">Disciplinary Measures</h4>
                                <p class="text-muted">The approach of the school to discipline has always been preventive and formative. It is not punitive but rather educative. 
                                    <br>A comprehensive and intense information campaign is initiated during the first few days of classes to ensure that all school rules and policies are communicated and understood by all concerned. 
                                    <br>A detailed warning system is in place for minor offenses. However, should all preventive measures and mechanisms fail, the school, through proper authorities, applies disciplinary measures or actions. 
                                    <br>Disciplinary measures or actions are meant to teach students the principles and ideals of justice to help them achieve self-discipline, as well as to enjoin them in developing and sustaining an atmosphere conducive to learning.</p>

                                <h5 class="sub-header">Corrective Actions to Minor and Major Offenses</h5>
                                <p>Corrective Actions are disciplinary measures that are imposed corresponding to the severity of the offense/s done by an erring student.</p>
                                
                                <h5 class="sub-header mt-4">Verbal/Oral Warning</h5>
                                <p>A Verbal/Oral warning is a disciplinary measure given to a student who has committed minor violations. This is to call the attention of the student that they have not observed the appropriate behavior expected of them. It is a reminder or reprimand to a student who committed a minor offense for the first time. This shall be included on the student's record since this shall be considered an initial warning.</p>

                                <h5 class="sub-header mt-4">Written Apology</h5>
                                <p>A Written Apology is a corrective action in which a student is required to write a letter of apology. This is imposed on a case-to-case basis and shall be included on the student's record.</p>

                                <h5 class="sub-header mt-4">Written Reprimand</h5>
                                <p>A Written Reprimand is a corrective action that is issued by the Discipline Committee. The student is given a formal letter or notice of any violation of the school rules and regulations. The student-specific misbehavior, together with the original copy of the written reprimand form, is put on the student's record.</p>

                                <h5 class="sub-header mt-4">Corrective Reinforcement</h5>
                                <p>During the period of corrective reinforcement, the student is still allowed to attend their classes. However, they have to be scheduled for one-on-one session after their last class period and to accomplish tasks as determined and given by the assigned person- in-authority of the Discipline Committee. The tasks under this sanction must help the student reflect and avoid repetition of the violated offense. The corrective reinforcement will be lifted a day after the specified date of rendering the sanction and after the completion of the task from a person-in-authority assigned by the Discipline Committee.</p>

                                <h5 class="sub-header mt-4">Conference with the Discipline Committee</h5>
                                <p>The parents/guardians are called for a conference with the Discipline Committee, Program Head, and/or Guidance Counselor for them to discuss the offense and the corresponding course of action to avoid the recurrence of the offense. This is required to be done to cases with the following sanctions: Written Apology, Written Reprimand, Corrective Reinforcement, Suspension, Non-readmission, Exclusion, and Expulsion.</p>


                                <h5 class="sub-header">Categories of Disciplinary Administrative Penalties</h5>
                                <p>According to the provisions in the 2009 Manual of Regulations for Private Higher Education (MORPHE), the four (4) categories of disciplinary administrative penalties for serious offenses of school rules and regulations which may be applied to an erring student are:</p>
                                <div class="Categories">
                                    <h5 class="sub-header mt-4">Suspension</h5>
                                    <p>In STI, this sanction has two (2) types:</p>
                                    <ol type="a" class="bulletin-list">
                                        <li>Suspension from class</li>
                                        <p>It is a penalty that excludes the offender from regular classwork and from other privileges or activities for a definite period of time. This is to be served within a reasonable time from the issuance of the decision of the Discipline Committee. A student under suspension is still required to report to school from 8 am to 5 pm but is not allowed to join their classes. They are required to do the task to be determined and supervised by the assigned person-in-authority of the Discipline Committee. The tasks under this sanction must help the student reflect and avoid repetition of the violated offense. Although they will be re-admitted to school, the suspension shall be put on the student's record. The suspension is imposed only after the parents or guardians have been informed through writing and invited to a conference with the Discipline Committee.</p>
                                        <li>Preventive Suspension</li>
                                        <p>A student under investigation may be preventively suspended from entering the school premises and from attending classes, when the evidence of guilt is strong and the responsible school official is morally convinced that the continued stay of the student during the period of the investigation constitutes a distraction to the normal operations of the school or poses a risk or danger to the life of persons and property in the school. The school is allowed to impose this sanction for a period not exceeding 20% of the prescribed class days for the school term. The suspension is imposed only after the parents or guardians have been informed through writing and conference with the Discipline Committee.</p>
                                    </ol>

                                    <h5 class="sub-header mt-4">Non-readmission</h5>
                                    <p>Non-readmission is a penalty in which the school is allowed to deny admission or enrollment of an erring student for the school term immediately following the term when the resolution or decision finding the student guilty of the offense charged and imposing the penalty of non-readmission was promulgated. Unlike the penalty of exclusion, the student is allowed to complete the current school term when the resolution of non-readmission was promulgated. Transfer Credentials of the erring student shall be issued upon promulgation, subject to the other provisions stated in the MORPHE.</p>
                                
                                    <h5 class="sub-header mt-4">Exclusion</h5>
                                    <p>Exclusion is a penalty in which the school is allowed to exclude or drop the name of the erring student from the roll of students immediately upon resolution for exclusion was promulgated. This penalty may be imposed for acts or offenses such as dishonesty, hazing, carrying deadly weapons, immorality, selling and/ or possession of prohibited drugs, drug dependency, drunkenness, hooliganism, vandalism, and other offenses analogous to the foregoing. Transfer Credentials of the erring student shall be issued upon promulgation, subject to the provisions stated in the MORPHE.</p>
                                    <p>The school shall preserve a complete record of the proceedings for a period of one (1) year in order to afford the Discipline Committee and Commission the opportunity to review the case in the event the student makes and files an appeal with the Commission on Higher Education.</p>
                                
                                    <h5 class="sub-header mt-4">Expulsion</h5>
                                    <p>Expulsion is a penalty in which an institution declares an erring student disqualified for admission to any public or private higher education institution in the Philippines.
                                        <br>In any case, the penalty of expulsion cannot be imposed without the approval of the Chairman of the Commission on Higher Education. This penalty may be imposed for acts or offenses involving moral turpitude or constituting gross misconduct, which are considered criminal pursuant to existing penal laws. 
                                        <br>The institution shall forward a complete record of the proceedings to the CHED Regional Office concerned within 10 days from the termination of the investigation of each case.</p>
                                </div>
                                <p>Imposition of sanctions cited in this handbook shall not in any way prejudice the filing of cases in and the implementation of penalties prescribed by a court of law. 
                                    <br>Also, in cases that involve significant damage or destruction of property, the Discipline Committee will decide whether the recipient of the sanction will replace the destroyed, damaged, or lost property. For cases of cheating in an examination or other school activities, a failing grade shall be given in the particular examination or activity. 
                                    <br>All sanctions shall go along with a one-on-one session with the School's Guidance Counselor or Associate.</p>
                            </section>



                            <!-- Modal area -->
                            @include('components.shared.modal.handbook-modal')

                           <!-- Addtional Contents -->
                            <section class="handbook-section-addtional-policies">
                                    @include('components.shared.partials.handbook-sections')
                            </section>

                            <section class="handbook-section-offenses">
                                <h4 class="fw-bold mt-4">Offenses</h4>
                                <p class="text-muted">Offenses are behaviors or visible actions exhibited by students that go against the school and institutional rules and regulations.</p>
                            
                                <h5 class="sub-header">Minor Offenses</h5>
                                <p>These are behaviors or actions that deviate or stray from the rules of the school or from student decorum and have minimal implications or consequences to the individual, other persons, the school, or the institution.</p>
                                <p>The sanctions imposed for the commission of these offenses are:</p>
                                <table class="mb-3">
                                    <thead>
                                      <tr>
                                        <td>First offense</td>
                                        <td>Verbal Warning</td>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td>Second offense</td>
                                        <td>Written Reprimand</td>
                                      </tr>
                                      <tr>
                                        <td>Third offense</td>
                                        <td>Written Reprimand &amp; Corrective Reinforcement (minimum of three (3) school days, maximum of seven (7) school days)</td>
                                      </tr>
                                    </tbody>
                                </table>
                                <p>Offenses under this category include but are not limited to the following:</p>
                                <ol class="numbered-list">
                                    <li>Non-adherence to the "STI Student Decorum</li>
                                    <li>Discourtesy towards any member of the STI community including campus visitors</li>
                                    <li>Non-wearing of school uniform, improper use of school uniform or ID inside school premises</li>
                                    <li>Wearing inappropriate campus attire</li>
                                    <li>Losing or forgetting one's ID three (3) times</li>
                                    <li>Disrespect to national symbols or any other similar infraction</li>
                                    <li>Irresponsible or improper use of school property</li>
                                    <li>Gambling in any form within the school premises or during official functions</li>
                                    <li>Staying or eating inside the classroom without permission from the school administrator or management</li>
                                    <li>Disruption of classes, school-sanctioned activities, and peace and order such as but not limited to:</li>
                                    <ul class="bulletin-list">
                                        <li>Failure to turn off or put into silent mode mobile phones and other similar gadgets</li>
                                        <li>Unauthorized use of social media, digital messaging, or any form of user account</li>
                                        <li>Unruly behavior (boisterous laughter, loitering, loud banter, uncontrolled giggling, and intentional misbehavior) or conduct during assemblies and the like</li>
                                    </ul>
                                    <li>Exhibiting displays of affection that negatively affect the reputation of the individuals</li>
                                    <li>Violation of classroom, laboratory, library, and other school offices procedure</li>
                                    <li>Possession of cigarettes or vapes</li>
                                    <li>Bringing of pets in the school premises</li>
                                </ol>

                                <h5 class="sub-header">Major Offenses - Category A</h5>
                                <p>These are behaviors or actions that deviate or stray from the rules of the school and/or from student decorum and have greater implications or consequences to the individual, other persons, and the school.</p>
                                <p>The sanctions imposed for the commission of these offenses are:</p>
                                <table class="mb-3">
                                    <thead>
                                      <tr>
                                        <td>First offense</td>
                                        <td>Written Reprimand & Corrective Reinforcement (minimum of three (3) school days, maximum of seven (7) school days)</td>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td>Second offense</td>
                                        <td>Suspension (minimum of three (3) school days, maximum of seven (7) school days)</td>
                                      </tr>
                                      <tr>
                                        <td>Third offense</td>
                                        <td>Non-readmission</td>
                                      </tr>
                                    </tbody>
                                </table>
                                <p>Offenses under this category include but are not limited to the following:</p>
                                <ol class="numbered-list">
                                    <li>More than three (3) commissions of any minor offense</li>
                                    <li>Lending/borrowing school ID, wearing, or using tampered ID</li>
                                    <li>Smoking or vaping inside the campus</li>
                                    <li>Entering the campus in a state of intoxication, bringing, and/or drinking liquor inside the campus</li>
                                    <li>Allowing a non-STI individual to enter the campus without official business or transaction</li>
                                    <li>Cheating that includes but is not limited to:</li>
                                    <ul class="bulletin-list">
                                        <li>Copying and/or willfully allowing another to copy during the administration of examination and/or assessments</li>
                                        <li>Using of "Codigo" or unauthorized resources or both during examination and/or assesments</li>
                                        <li>Plagiarism</li>
                                        <li>Communicating with another student or person in any form during an examination or test without permission from the teacher or proctor.</li>
                                        <li>Having somebody else take an examination or test for one's self or prepare a required report or assignment. If both parties are students, both are liable.</li>
                                        <li>Leaking of examination questions or answer keys to another student/s in any form</li>
                                    </ul>
                                </ol>
                                <h5 class="sub-header">Major Offenses - Category B</h5>
                                <p>These are behaviors or actions that lead to damage or destruction of property or image or both of an individual, a group, the school, or the institution.</p>
                                <p>The sanctions imposed for the commission of these offenses are:</p>
                                <table class="mb-3">
                                    <thead>
                                      <tr>
                                        <td>First offense</td>
                                        <td>Suspension (minimum of three (3) school days, maximum of seven (7) school days)</td>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td>Second offense</td>
                                        <td>Non-readmission</td>
                                      </tr>
                                    </tbody>
                                </table>
                                <p>Offenses under this category include but are not limited to the following:</p>
                                <ol class="numbered-list">
                                    <li>Vandalizing, damaging, or destroying of property belonging to any member of the STI community, visitors, or guests while in the school campus</li>
                                    <li>Posting or uploading of statements, photos, videos, or other graphical images disrespectful to the STI Brand, another student, faculty member, or any other individual</li>
                                    <li> Recording and uploading of photos, videos, or other graphical images that violate the data privacy of another student, faculty member, or any other individual</li>
                                    <li>Going to places of ill repute while wearing the school uniform</li>
                                    <li>Issuing a false testimony during official investigations</li>
                                    <li>Use of profane language that expresses grave insult toward any member of the STI community</li>
                                </ol>

                                <h5 class="sub-header">Major Offenses - Category C</h5>
                                <p>These are behaviors or actions that lead to any of the following:</p>
                                <ul class="bulletin-list">
                                    <li>significant injury to the individual or other persons</li>
                                    <li>endangering the safety and welfare of the individual and other persons</li>
                                    <li>degrading the integrity of the person, school, or the institution</li>
                                </ul>
                                <p>The sanctions imposed for the commission of these offenses are:</p>
                                <table class="mb-3">
                                    <thead>
                                      <tr>
                                        <td>First offense</td>
                                        <td>Suspension (minimum of seven (7) school days, maximum of 10 school days)</td>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td>Second offense</td>
                                        <td>Non-readmission</td>
                                      </tr>
                                    </tbody>
                                </table>
                                <p>Offenses under this category include but are not limited to the following:</p>
                                <ol class="numbered-list">
                                    <li>"Hacking" attacks on the computer system of the school or other institutions or both</li>
                                    <li>Stealing, tampering, or forgery of records and receipts</li>
                                    <li>Theft or robbery of school property or those belonging to school officials, teachers, personnel, other students, any member of the STI community, visitors, and guests</li>
                                    <li>Unauthorized copying, distribution, modification, or exhibition - in whole or in part - of eLMS materials or other learning materials provided by STI such as but not limited to videos, PowerPoint presentations, handouts, activity/worksheets, and answer keys. This will include:</li>
                                    <ul class="bulletin-list">
                                        <li>use of the materials for any commercial purpose or for any public display (commercial or non-commercial)</li>
                                        <li>attempt to decompile or reverse engineer any software contained on the eLMS</li>
                                        <li>remove any copyright or other proprietary notations from the materials</li>
                                        <li>transfer the materials to another person or "mirror" the materials on any other server or sites</li>
                                    </ul>
                                    <li>Embezzlement and malversation of school or organization funds or property</li>
                                    <li>Disruption of academic functions or school activities through illegal assemblies, demonstrations, boycotts, pickets, or mass actions or related activities, which tend to create public disorder or disturbance</li>
                                    <li>Any act of immorality</li>
                                    <li>Any act of bullying (such as but not limited to physical, cyber, and verbal)</li>
                                    <li>Participation in brawls or infliction of physical injuries within or outside school premises, whether in school uniform or not</li>
                                    <li>Physical assault upon another within or outside the school premises, whether in school uniform or not</li>
                                    <li>Use of prohibited drugs or chemicals in any form within and outside the school premises, whether in uniform or not</li>
                                    <li>Giving false or malicious fire alarms and bomb threats</li>
                                    <li>Use of fire protective or firefighting equipment of the school other than for firefighting except in other emergencies where their use is justified.</li>
                                </ol>

                                <h5 class="sub-header">Major Offenses - Category D</h5>
                                <p>These are behaviors or actions that are in direct violation of the Philippine Laws. 
                                    <br>The sanction imposed for the commission of these offenses is either Exclusion/Expulsion.</p>
                                <p>Offenses under this category include but are not limited to the following:</p>
                                <ol class="numbered-list">
                                    <li>Possession or sale of prohibited drugs or chemicals in any form, or any illegal drug paraphernalia within and outside the school premises whether in uniform or not</li>
                                    <li>Continued use and being found to be "confirmed positive" of using prohibited drugs or chemicals for the second time, even after undergoing an intervention.</li>
                                    <li>Carrying or possession of firearms, deadly weapons, and explosives within and outside the school premises, whether in uniform or not</li>
                                    <li>Membership or affiliation in organizations, such as but not limited to fraternities and sororities, that employ or advocate illegal rites or ceremonies, which include hazing and initiation</li>
                                    <li>Participation in illegal rites, ceremonies, and ordeals, which includes hazing and initiation</li>
                                    <li>Commission of crime involving moral turpitude (such as but not limited to rape, forgery, estafa, acts of lasciviousness, moral depravity, murder, and homicide)</li>
                                    <li>Commission of acts constituting sexual harassment as defined in the Student Manual and Republic Act 7877, otherwise known as the "Anti-Sexual Harassment Act of 1995"</li>
                                    <li>Acts of subversion, sedition, or insurgency</li>
                                </ol>

                                <h5 class="sub-header">Disciplinary Cases or Offenses Not Written in the Student Handbook</h5>
                                <p>Disciplinary cases or offenses not written in the Student Handbook are subject to the review of the Discipline Committee and school administration in the interest of upholding the ideal learning environment and of the STI Community.</p>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
<script src="{{ asset('./vendor/bootstrap.bundle.min.js') }}"></script>
<script src="FacultyJS/StudentHandbook.js"></script>

</body>

</html>