import React from 'react';
import { useNavigate } from 'react-router-dom';

const StatCard = ({ title, value, icon: Icon, colorClass, linkTo }) => {
  const navigate = useNavigate();

  return (
    <div 
      className="card stat-card" 
      onClick={() => navigate(linkTo)}
      style={{ cursor: 'pointer', transition: 'transform 0.2s', display: 'flex', alignItems: 'center', gap: '1.5rem' }}
      onMouseEnter={(e) => e.currentTarget.style.transform = 'translateY(-4px)'}
      onMouseLeave={(e) => e.currentTarget.style.transform = 'translateY(0)'}
    >
      <div className={`icon-badge ${colorClass}`} style={{ 
        width: '56px', height: '56px', borderRadius: '50%', display: 'flex', alignItems: 'center', justifyContent: 'center' 
      }}>
        <Icon size={24} color="white" />
      </div>
      <div>
        <h3 style={{ fontSize: '0.875rem', color: 'var(--text-muted)', fontWeight: 500, margin: 0, textTransform: 'uppercase', letterSpacing: '0.05em' }}>
          {title}
        </h3>
        <p style={{ fontSize: '2rem', fontWeight: 700, color: 'var(--text-main)', margin: '0.25rem 0 0 0' }}>
          {value}
        </p>
      </div>
    </div>
  );
};

export default StatCard;
