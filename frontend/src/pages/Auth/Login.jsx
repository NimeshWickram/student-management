import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../../api/axios';
import styles from './Login.module.css';
import { KeyRound, Mail } from 'lucide-react';

const Login = () => {
  const navigate = useNavigate();
  const [role, setRole] = useState('admin');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleLogin = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);

    try {
      let endpoint = '';
      if (role === 'admin') endpoint = '/api/login';
      else if (role === 'teacher') endpoint = '/api/teacher/login';
      else if (role === 'student') endpoint = '/api/student/login';

      const response = await api.post(endpoint, { email, password });
      
      if (response.data && response.data.token) {
        localStorage.setItem('token', response.data.token);
        localStorage.setItem('role', response.data.role);
        localStorage.setItem('user', JSON.stringify(response.data.user));
        
        navigate('/');
      } else {
        setError('Invalid response from server.');
      }
    } catch (err) {
      setError(err.response?.data?.message || 'Login failed. Please check your credentials.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className={styles.loginContainer}>
      <div className={styles.loginCard}>
        <div className={styles.brandHeader}>
          <h2>CodeXpress</h2>
          <p>Login to your account</p>
        </div>

        <div className={styles.roleTabs}>
          <button 
            className={`${styles.roleTab} ${role === 'admin' ? styles.activeTab : ''}`}
            onClick={() => setRole('admin')}
            type="button"
          >
            Admin
          </button>
          <button 
            className={`${styles.roleTab} ${role === 'teacher' ? styles.activeTab : ''}`}
            onClick={() => setRole('teacher')}
            type="button"
          >
            Teacher
          </button>
          <button 
            className={`${styles.roleTab} ${role === 'student' ? styles.activeTab : ''}`}
            onClick={() => setRole('student')}
            type="button"
          >
            Student
          </button>
        </div>

        {error && <div className={styles.errorMessage}>{error}</div>}

        <form onSubmit={handleLogin} className={styles.loginForm}>
          <div className="form-group">
            <label className="form-label">Email Address</label>
            <div className={styles.inputWrapper}>
              <Mail className={styles.inputIcon} size={18} />
              <input 
                type="email" 
                className="form-input" 
                style={{ paddingLeft: '2.5rem' }}
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="Enter your email"
                required
              />
            </div>
          </div>

          <div className="form-group">
            <label className="form-label">Password</label>
            <div className={styles.inputWrapper}>
              <KeyRound className={styles.inputIcon} size={18} />
              <input 
                type="password" 
                className="form-input"
                style={{ paddingLeft: '2.5rem' }}
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="Enter your password"
                required
              />
            </div>
          </div>

          <button type="submit" className={`btn btn-primary ${styles.submitBtn}`} disabled={loading}>
            {loading ? 'Authenticating...' : 'Sign In'}
          </button>
        </form>
      </div>
    </div>
  );
};

export default Login;
