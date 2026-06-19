import React from 'react';
import { NavLink, useNavigate } from 'react-router-dom';
import { LayoutDashboard, Users, UserCheck, BookOpen, FileText, User, LogOut } from 'lucide-react';
import api from '../../api/axios';
import styles from './Layout.module.css';

const Sidebar = () => {
  const navigate = useNavigate();

  const handleLogout = async () => {
    try {
      await api.post('/api/logout');
    } catch (e) {
      console.error('Logout failed', e);
    } finally {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      localStorage.removeItem('role');
      navigate('/login');
    }
  };

  const navLinkClass = ({ isActive }) => 
    `${styles.navLink} ${isActive ? styles.navLinkActive : ''}`;

  return (
    <aside className={styles.sidebar}>
      <div className={styles.brand}>
        <h2>CodeXpress</h2>
        <p>Admin Portal</p>
      </div>

      <div className={styles.navGroup}>
        <h3 className={styles.navHeader}>Main</h3>
        <NavLink to="/" className={navLinkClass} end>
          <LayoutDashboard size={18} /> Dashboard
        </NavLink>
      </div>

      <div className={styles.navGroup}>
        <h3 className={styles.navHeader}>Management</h3>
        <NavLink to="/students" className={navLinkClass}>
          <Users size={18} /> Students
        </NavLink>
        <NavLink to="/teachers" className={navLinkClass}>
          <UserCheck size={18} /> Teachers
        </NavLink>
        <NavLink to="/subjects" className={navLinkClass}>
          <BookOpen size={18} /> Subjects
        </NavLink>
        <NavLink to="/quizzes" className={navLinkClass}>
          <FileText size={18} /> Quizzes
        </NavLink>
      </div>

      <div className={styles.spacer}></div>

      <div className={styles.navGroup}>
        <button onClick={() => navigate('/profile')} className={styles.navLink}>
          <User size={18} /> My Profile
        </button>
        <button onClick={handleLogout} className={`${styles.navLink} ${styles.navLinkDanger}`}>
          <LogOut size={18} /> Logout
        </button>
      </div>
    </aside>
  );
};

export default Sidebar;
