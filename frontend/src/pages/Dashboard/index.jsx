import React, { useEffect, useState } from 'react';
import { Users, UserCheck, BookOpen } from 'lucide-react';
import api from '../../api/axios';
import StatCard from '../../components/StatCard';

const Dashboard = () => {
  const [stats, setStats] = useState({
    students: 0,
    teachers: 0,
    subjects: 0
  });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchStats = async () => {
      try {
        const [studentsRes, teachersRes, subjectsRes] = await Promise.all([
          api.get('/api/students'),
          api.get('/api/teachers'),
          api.get('/api/subjects')
        ]);
        
        setStats({
          students: studentsRes.data.total || studentsRes.data.data?.length || 0,
          teachers: teachersRes.data.total || teachersRes.data.data?.length || 0,
          subjects: subjectsRes.data.total || subjectsRes.data.data?.length || 0
        });
      } catch (error) {
        console.error('Error fetching dashboard stats', error);
      } finally {
        setLoading(false);
      }
    };

    fetchStats();
  }, []);

  if (loading) return <div>Loading dashboard...</div>;

  return (
    <div>
      <h1 style={{ fontSize: '1.5rem', fontWeight: 700, marginBottom: '2rem' }}>Dashboard</h1>
      
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '1.5rem' }}>
        <StatCard 
          title="Total Students" 
          value={stats.students} 
          icon={Users} 
          colorClass="bg-indigo" 
          linkTo="/students"
        />
        <StatCard 
          title="Total Teachers" 
          value={stats.teachers} 
          icon={UserCheck} 
          colorClass="bg-green" 
          linkTo="/teachers"
        />
        <StatCard 
          title="Total Subjects" 
          value={stats.subjects} 
          icon={BookOpen} 
          colorClass="bg-amber" 
          linkTo="/subjects"
        />
      </div>
    </div>
  );
};

export default Dashboard;
