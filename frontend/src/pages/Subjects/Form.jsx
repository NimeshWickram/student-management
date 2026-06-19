import React, { useState, useEffect } from 'react';
import Modal from '../../components/Modal';

const Form = ({ isOpen, onClose, onSubmit, initialData }) => {
  const [formData, setFormData] = useState({
    name: '',
    code: '',
    description: '',
    credits: ''
  });

  useEffect(() => {
    if (initialData) {
      setFormData(initialData);
    } else {
      setFormData({
        name: '',
        code: '',
        description: '',
        credits: ''
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
    <Modal isOpen={isOpen} onClose={onClose} title={initialData ? "Edit Subject" : "Add Subject"}>
      <form onSubmit={handleSubmit}>
        <div style={{ display: 'grid', gridTemplateColumns: '2fr 1fr', gap: '1rem' }}>
          <div className="form-group">
            <label className="form-label">Subject Name</label>
            <input type="text" name="name" className="form-input" value={formData.name} onChange={handleChange} required />
          </div>
          <div className="form-group">
            <label className="form-label">Subject Code</label>
            <input type="text" name="code" className="form-input" value={formData.code} onChange={handleChange} required />
          </div>
        </div>

        <div className="form-group">
          <label className="form-label">Credits</label>
          <input type="number" name="credits" className="form-input" value={formData.credits} onChange={handleChange} required min="1" max="10" />
        </div>

        <div className="form-group">
          <label className="form-label">Description</label>
          <textarea 
            name="description" 
            className="form-input" 
            value={formData.description} 
            onChange={handleChange} 
            rows="3" 
            required 
            style={{ resize: 'vertical' }}
          ></textarea>
        </div>

        <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '0.75rem', marginTop: '1.5rem' }}>
          <button type="button" className="btn btn-secondary" onClick={onClose}>Cancel</button>
          <button type="submit" className="btn btn-primary">{initialData ? 'Save Changes' : 'Add Subject'}</button>
        </div>
      </form>
    </Modal>
  );
};

export default Form;
