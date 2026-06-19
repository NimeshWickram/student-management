import React from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';

import Layout from './components/Layout';
import Login from './pages/Auth/Login';
import Dashboard from './pages/Dashboard';
import StudentsList from './pages/Students/List';
import TeachersList from './pages/Teachers/List';
import SubjectsList from './pages/Subjects/List';
import QuizzesList from './pages/Quizzes/List';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/login" element={<Login />} />
        
        <Route path="/" element={<Layout />}>
          <Route index element={<Dashboard />} />
          <Route path="students" element={<StudentsList />} />
          <Route path="teachers" element={<TeachersList />} />
          <Route path="subjects" element={<SubjectsList />} />
          <Route path="quizzes" element={<QuizzesList />} />
          <Route path="profile" element={<div style={{ padding: '2rem' }}><h2>My Profile</h2><p>Coming soon...</p></div>} />
        </Route>

        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
