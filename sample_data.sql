USE job_portal;
-- Job Seeker page samples
-- Sample job seekers
INSERT INTO job_seekers (full_name, job_title, skills, about) VALUES
('Amina Al-Farouqi', 'Web Developer', 'HTML, CSS, JavaScript, React', 'Passionate front-end developer with 3 years of experience...'),
('william Smith', 'Data Analyst', 'Python, SQL, Tableau, Excel', 'Data enthusiast with strong analytical skills...'),
('Omar al-mansori', 'UX Designer', 'Figma, Adobe XD, User Research', 'Creating intuitive user experiences...'),
('layla hassan', 'DevOps Engineer', 'AWS, Docker, Kubernetes, CI/CD', 'Helping teams deliver software faster...'),
('Qaboos Al-Rashdi', 'Product Manager', 'Agile, Scrum, Product Strategy', 'Bridging the gap between business needs...');

-- Sample saved jobs
INSERT INTO saved_jobs (seeker_id, job_title, company, location, description) VALUES
(1, 'Senior Frontend Developer', 'Tech Solutions Inc.', 'Dubai, UAE', 'Looking for an experienced React developer...'),
(1, 'UI/UX Designer', 'Digital Creations', 'Abu Dhabi, UAE', 'Join our creative team...'),
(2, 'Data Scientist', 'Analytics Pro', 'Riyadh, KSA', 'Work with big data...'),
(3, 'UX Researcher', 'UserFirst', 'Dubai, UAE', 'Conduct user studies...'),
(4, 'Cloud Architect', 'Cloud Masters', 'Remote', 'Design and implement cloud...');

-- Sample application statuses
INSERT INTO application_status (seeker_id, job_title, company, status) VALUES
(1, 'Lead UX Designer', 'Design Innovators', 'Under Review'),
(1, 'Software Engineer', 'Tech Giants', 'Offer Received'),
(2, 'Data Analyst', 'Numbers Inc.', 'Interview Scheduled'),
(3, 'Junior Designer', 'Creative Minds', 'Applied'),
(4, 'Systems Administrator', 'IT Solutions', 'Rejected');


-- Employer page Samples:
-- Sample employers
INSERT INTO employers (company_name, location, industry, description) VALUES
('TechVision', 'San Francisco, CA', 'Technology', 'Leading tech company specializing in AI solutions'),
('Oman Digital Solutions', 'Muscat, Oman', 'IT Services', 'Digital transformation experts for the Gulf region'),
('Healthcare Plus', 'Dubai, UAE', 'Healthcare', 'Providing innovative healthcare solutions'),
('EduFuture', 'Riyadh, KSA', 'Education', 'Online education platform for professional development'),
('GreenBuild', 'Abu Dhabi, UAE', 'Construction', 'Sustainable construction technologies');

-- Sample posted jobs
INSERT INTO posted_jobs (employer_id, title, salary, description, location) VALUES
(1, 'Senior Software Engineer', '$120,000 - $150,000', 'Develop high-performance applications and systems...', 'San Francisco, CA'),
(2, 'Application Developer', 'OMR 1,200 - OMR 1,800', 'Develops and customizes software applications...', 'Muscat, Oman'),
(3, 'Medical Director', 'AED 35,000 - AED 45,000', 'Oversee clinical operations and quality assurance...', 'Dubai, UAE'),
(4, 'E-Learning Specialist', 'SAR 15,000 - SAR 20,000', 'Design and develop online course materials...', 'Remote'),
(5, 'Sustainability Engineer', 'AED 25,000 - AED 30,000', 'Implement green building practices...', 'Abu Dhabi, UAE');

-- Sample applications (assuming some job seekers exist)
INSERT INTO job_applications (job_id, seeker_id, status, last_contact) VALUES
(1, 1, 'Under Review', '2023-05-10 14:30:00'),
(1, 2, 'Interview Scheduled', '2023-05-12 09:15:00'),
(2, 3, 'Offer Received', '2023-05-15 16:45:00'),
(3, 4, 'Applied', '2023-05-01 11:20:00'),
(4, 5, 'Rejected', '2023-05-05 13:10:00');

-- Sample messages
INSERT INTO messages (application_id, sender_type, message_text) VALUES
(1, 'Employer', 'Thank you for your application. We are reviewing your materials.'),
(1, 'Candidate', 'Thank you! When can I expect to hear back?'),
(2, 'Employer', 'We would like to schedule an interview for next week.'),
(3, 'Employer', 'We are pleased to offer you the position!');