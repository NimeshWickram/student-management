import React, { useState, useEffect } from 'react';
import Modal from '../../components/Modal';
import { Plus, Trash2 } from 'lucide-react';
import api from '../../api/axios';

const Form = ({ isOpen, onClose, onSubmit, initialData }) => {
  const [teachers, setTeachers] = useState([]);
  const [subjects, setSubjects] = useState([]);

  const [formData, setFormData] = useState({
    title: '',
    teacher_id: '',
    subject_id: '',
    grade: '',
    quiz_type: 'manual_mcq',
  });

  // For manual_mcq
  const [manualContent, setManualContent] = useState([
    { question: '', options: ['', '', '', ''], correct_option: 0 }
  ]);

  // For pdf_mcq
  const [pdfFile, setPdfFile] = useState(null);

  useEffect(() => {
    if (isOpen) {
      fetchDropdowns();
    }
  }, [isOpen]);

  useEffect(() => {
    if (initialData && isOpen) {
      setFormData({
        title: initialData.title || '',
        teacher_id: initialData.teacher_id || '',
        subject_id: initialData.subject_id || '',
        grade: initialData.grade || '',
        quiz_type: initialData.quiz_type || 'manual_mcq',
      });
      if (initialData.quiz_type === 'manual_mcq' && initialData.manual_content) {
        let parsed = initialData.manual_content;
        if (typeof parsed === 'string') {
          try { parsed = JSON.parse(parsed); } catch(e) {}
        }
        if (Array.isArray(parsed) && parsed.length > 0) {
          setManualContent(parsed);
        }
      }
      setPdfFile(null); // Clear file input on edit
    } else {
      setFormData({
        title: '',
        teacher_id: '',
        subject_id: '',
        grade: '',
        quiz_type: 'manual_mcq',
      });
      setManualContent([{ question: '', options: ['', '', '', ''], correct_option: 0 }]);
      setPdfFile(null);
    }
  }, [initialData, isOpen]);

  const fetchDropdowns = async () => {
    try {
      const [techRes, subRes] = await Promise.all([
        api.get('/api/teachers?limit=100'),
        api.get('/api/subjects?limit=100')
      ]);
      setTeachers(techRes.data.data || techRes.data);
      setSubjects(subRes.data.data || subRes.data);
    } catch (e) {
      console.error('Error fetching dropdowns', e);
    }
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  // Dynamic question handlers
  const addQuestion = () => {
    setManualContent(prev => [...prev, { question: '', options: ['', '', '', ''], correct_option: 0 }]);
  };

  const removeQuestion = (index) => {
    setManualContent(prev => prev.filter((_, i) => i !== index));
  };

  const handleQuestionChange = (index, field, value) => {
    const newContent = [...manualContent];
    newContent[index][field] = value;
    setManualContent(newContent);
  };

  const handleOptionChange = (qIndex, oIndex, value) => {
    const newContent = [...manualContent];
    newContent[qIndex].options[oIndex] = value;
    setManualContent(newContent);
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (formData.quiz_type === 'pdf_mcq') {
      const fd = new FormData();
      Object.keys(formData).forEach(key => fd.append(key, formData[key]));
      if (pdfFile) {
        fd.append('pdf_file', pdfFile);
      }
      onSubmit(fd);
    } else {
      // JSON payload for manual_mcq
      const payload = { ...formData, manual_content: manualContent };
      onSubmit(payload);
    }
  };

  return (
    <Modal isOpen={isOpen} onClose={onClose} title={initialData ? "Edit Quiz" : "Add Quiz"}>
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label className="form-label">Quiz Title</label>
          <input type="text" name="title" className="form-input" value={formData.title} onChange={handleChange} required />
        </div>

        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem' }}>
          <div className="form-group">
            <label className="form-label">Teacher</label>
            <select name="teacher_id" className="form-input" value={formData.teacher_id} onChange={handleChange} required>
              <option value="">Select Teacher</option>
              {teachers.map(t => <option key={t.id} value={t.id}>{t.first_name} {t.last_name}</option>)}
            </select>
          </div>
          <div className="form-group">
            <label className="form-label">Subject</label>
            <select name="subject_id" className="form-input" value={formData.subject_id} onChange={handleChange} required>
              <option value="">Select Subject</option>
              {subjects.map(s => <option key={s.id} value={s.id}>{s.name}</option>)}
            </select>
          </div>
        </div>

        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem' }}>
          <div className="form-group">
            <label className="form-label">Grade</label>
            <select name="grade" className="form-input" value={formData.grade} onChange={handleChange} required>
              <option value="">Select Grade</option>
              {[...Array(11)].map((_, i) => (
                <option key={i+1} value={`Grade ${i+1}`}>Grade {i+1}</option>
              ))}
            </select>
          </div>
          <div className="form-group">
            <label className="form-label">Quiz Type</label>
            <select name="quiz_type" className="form-input" value={formData.quiz_type} onChange={handleChange} required>
              <option value="manual_mcq">Manual MCQ Builder</option>
              <option value="pdf_mcq">PDF Upload</option>
            </select>
          </div>
        </div>

        {formData.quiz_type === 'pdf_mcq' && (
          <div className="form-group" style={{ padding: '1.5rem', border: '2px dashed var(--border-color)', borderRadius: '0.5rem', textAlign: 'center' }}>
            <label className="form-label">Upload PDF File</label>
            <input 
              type="file" 
              accept=".pdf" 
              onChange={(e) => setPdfFile(e.target.files[0])} 
              style={{ display: 'block', margin: '0 auto' }}
              required={!initialData} // only required on create
            />
          </div>
        )}

        {formData.quiz_type === 'manual_mcq' && (
          <div style={{ marginTop: '1.5rem', borderTop: '1px solid var(--border-color)', paddingTop: '1rem' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1rem' }}>
              <h3 style={{ fontSize: '1rem', fontWeight: 600 }}>Questions</h3>
              <button type="button" className="btn btn-secondary" onClick={addQuestion} style={{ padding: '0.25rem 0.5rem' }}>
                <Plus size={14} /> Add Question
              </button>
            </div>
            
            <div style={{ maxHeight: '300px', overflowY: 'auto', paddingRight: '0.5rem' }}>
              {manualContent.map((q, qIndex) => (
                <div key={qIndex} style={{ background: 'var(--bg-color)', padding: '1rem', borderRadius: '0.5rem', marginBottom: '1rem', position: 'relative' }}>
                  {manualContent.length > 1 && (
                    <button type="button" onClick={() => removeQuestion(qIndex)} style={{ position: 'absolute', top: '0.5rem', right: '0.5rem', background: 'none', border: 'none', color: 'var(--color-red)' }}>
                      <Trash2 size={16} />
                    </button>
                  )}
                  <div className="form-group">
                    <label className="form-label">Question {qIndex + 1}</label>
                    <input type="text" className="form-input" value={q.question} onChange={(e) => handleQuestionChange(qIndex, 'question', e.target.value)} required />
                  </div>
                  <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '0.5rem' }}>
                    {q.options.map((opt, oIndex) => (
                      <div key={oIndex} style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
                        <input 
                          type="radio" 
                          name={`correct_${qIndex}`} 
                          checked={q.correct_option === oIndex} 
                          onChange={() => handleQuestionChange(qIndex, 'correct_option', oIndex)} 
                        />
                        <input 
                          type="text" 
                          className="form-input" 
                          placeholder={`Option ${oIndex + 1}`} 
                          value={opt} 
                          onChange={(e) => handleOptionChange(qIndex, oIndex, e.target.value)} 
                          style={{ padding: '0.4rem 0.75rem' }}
                          required 
                        />
                      </div>
                    ))}
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}

        <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '0.75rem', marginTop: '1.5rem' }}>
          <button type="button" className="btn btn-secondary" onClick={onClose}>Cancel</button>
          <button type="submit" className="btn btn-primary">{initialData ? 'Save Changes' : 'Add Quiz'}</button>
        </div>
      </form>
    </Modal>
  );
};

export default Form;
