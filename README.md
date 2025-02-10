This project allows users to upload their resumes in PDF format, and it analyzes them based on ATS (Applicant Tracking System) standards. The system extracts text content from the resume, checks for essential keywords (like experience, education, skills, and certifications), and provides a score and feedback to help users improve their resumes.

Features of the Project:
✅ User Interface (Frontend)
Simple and clean UI for uploading resumes.
Form fields to enter name and email.
Upload button with validation (only PDFs allowed).
CSS Styling for a professional look.
✅ Backend (PHP & MySQL)
Handles file uploads securely.
Extracts text from PDF resumes using the Smalot\PdfParser\Parser library.
Analyzes resume content based on ATS-friendly keywords (experience, education, skills, projects, certifications).
Generates an ATS Score (out of 100) based on keyword matching.
Provides improvement suggestions if key sections are missing.
Stores resume details and ATS score in MySQL Database for future reference.
✅ Database (MySQL)
Stores user details, uploaded file names, ATS score, and improvement suggestions.
Users can retrieve their past resume analysis results.
