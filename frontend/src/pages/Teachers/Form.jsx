import React, { useState, useEffect } from 'react';
import Modal from '../../components/Modal';

const Form = ({ isOpen, onClose, onSubmit, initialData }) => {
  const [formData, setFormData] = useState({
    first_name: '',
    last_name: '',
    email: '',
    phone_number: '',
    subject: '',
    salutation: 'Mr.',
    gender: 'Male'
  });

  useEffect(() => {
    if (initialData) {
      setFormData(initialData);
    } else {
      setFormData({
        first_name: '',
        last_name: '',
        email: '',
        phone_number: '',
        subject: '',
        salutation: 'Mr.',
        gender: 'Male'
      });
    }
  }, [initialData, isOpen]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    onSubmit(formData);
  };

  return (
    <Modal isOpen={isOpen} onClose={onClose} title={initialData ? "Edit Teacher" : "Add Teacher"}>
      <form onSubmit={handleSubmit}>
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 2fr', gap: '1rem', marginBottom: '1rem' }}>
          <div className="form-group" style={{ marginBottom: 0 }}>
            <label className="form-label">Salutation</label>
            <select name="salutation" className="form-input" value={formData.salutation} onChange={handleChange} required>
              <option value="Mr.">Mr.</option>
              <option value="Mrs.">Mrs.</option>
              <option value="Ms.">Ms.</option>
              <option value="Dr.">Dr.</option>
              <option value="Prof.">Prof.</option>
            </select>
          </div>
          <div className="form-group" style={{ marginBottom: 0 }}>
            <label className="form-label">First Name</label>
            <input type="text" name="first_name" className="form-input" value={formData.first_name} onChange={handleChange} required />
          </div>
        </div>

        <div className="form-group">
          <label className="form-label">Last Name</label>
          <input type="text" name="last_name" className="form-input" value={formData.last_name} onChange={handleChange} required />
        </div>
        
        <div className="form-group">
          <label className="form-label">Email</label>
          <input type="email" name="email" className="form-input" value={formData.email} onChange={handleChange} required />
        </div>

        <div className="form-group">
          <label className="form-label">Phone Number</label>
          <input type="text" name="phone_number" className="form-input" value={formData.phone_number} onChange={handleChange} required />
        </div>

        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem' }}>
          <div className="form-group">
            <label className="form-label">Subject</label>
            <input type="text" name="subject" className="form-input" value={formData.subject} onChange={handleChange} required />
          </div>
          <div className="form-group">
            <label className="form-label">Gender</label>
            <select name="gender" className="form-input" value={formData.gender} onChange={handleChange} required>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Other">Other</option>
            </select>
          </div>
        </div>

        <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '0.75rem', marginTop: '1.5rem' }}>
          <button type="button" className="btn btn-secondary" onClick={onClose}>Cancel</button>
          <button type="submit" className="btn btn-primary">{initialData ? 'Save Changes' : 'Add Teacher'}</button>
        </div>
      </form>
    </Modal>
  );
};

export default Form;
