import React, { useState, useEffect } from 'react';
import Modal from '../../components/Modal';

const Form = ({ isOpen, onClose, onSubmit, initialData }) => {
  const [formData, setFormData] = useState({
    first_name: '',
    last_name: '',
    email: '',
    phone_number: '',
    course: '',
    grade: ''
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
        course: '',
        grade: ''
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
    <Modal isOpen={isOpen} onClose={onClose} title={initialData ? "Edit Student" : "Add Student"}>
      <form onSubmit={handleSubmit}>
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem' }}>
          <div className="form-group">
            <label className="form-label">First Name</label>
            <input type="text" name="first_name" className="form-input" value={formData.first_name} onChange={handleChange} required />
          </div>
          <div className="form-group">
            <label className="form-label">Last Name</label>
            <input type="text" name="last_name" className="form-input" value={formData.last_name} onChange={handleChange} required />
          </div>
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
            <label className="form-label">Course</label>
            <input type="text" name="course" className="form-input" value={formData.course} onChange={handleChange} required />
          </div>
          <div className="form-group">
            <label className="form-label">Grade</label>
            <select name="grade" className="form-input" value={formData.grade} onChange={handleChange} required>
              <option value="">Select Grade</option>
              {[...Array(11)].map((_, i) => (
                <option key={i+1} value={`Grade ${i+1}`}>Grade {i+1}</option>
              ))}
            </select>
          </div>
        </div>

        <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '0.75rem', marginTop: '1.5rem' }}>
          <button type="button" className="btn btn-secondary" onClick={onClose}>Cancel</button>
          <button type="submit" className="btn btn-primary">{initialData ? 'Save Changes' : 'Add Student'}</button>
        </div>
      </form>
    </Modal>
  );
};

export default Form;
