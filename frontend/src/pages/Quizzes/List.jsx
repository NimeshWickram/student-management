import React, { useState, useEffect } from 'react';
import { getQuizzes, createQuiz, updateQuiz, deleteQuiz } from './api';
import Form from './Form';
import { Edit2, Trash2, Plus, Search, ChevronLeft, ChevronRight, FileCheck, File } from 'lucide-react';

const List = () => {
  const [quizzes, setQuizzes] = useState([]);
  const [pagination, setPagination] = useState({});
  const [page, setPage] = useState(1);
  const [search, setSearch] = useState('');
  const [loading, setLoading] = useState(true);
  
  const [isFormOpen, setIsFormOpen] = useState(false);
  const [editingQuiz, setEditingQuiz] = useState(null);

  const fetchQuizzes = async () => {
    setLoading(true);
    try {
      const data = await getQuizzes(page, search);
      setQuizzes(data.data);
      setPagination({
        current_page: data.current_page,
        last_page: data.last_page,
        total: data.total
      });
    } catch (error) {
      console.error('Failed to fetch quizzes', error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    const delayDebounceFn = setTimeout(() => {
      fetchQuizzes();
    }, 300);
    return () => clearTimeout(delayDebounceFn);
  }, [page, search]);

  const handleSearch = (e) => {
    setSearch(e.target.value);
    setPage(1);
  };

  const handleOpenAdd = () => {
    setEditingQuiz(null);
    setIsFormOpen(true);
  };

  const handleOpenEdit = (quiz) => {
    setEditingQuiz(quiz);
    setIsFormOpen(true);
  };

  const handleDelete = async (id) => {
    if (window.confirm('Are you sure you want to delete this quiz?')) {
      try {
        await deleteQuiz(id);
        fetchQuizzes();
      } catch (error) {
        console.error('Failed to delete quiz', error);
      }
    }
  };

  const handleFormSubmit = async (data) => {
    try {
      if (editingQuiz) {
        await updateQuiz(editingQuiz.id, data);
      } else {
        await createQuiz(data);
      }
      setIsFormOpen(false);
      fetchQuizzes();
    } catch (error) {
      console.error('Failed to save quiz', error);
      alert('Failed to save quiz. Check console for details.');
    }
  };

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.5rem' }}>
        <h1 style={{ fontSize: '1.5rem', fontWeight: 700 }}>Quizzes</h1>
        <button className="btn btn-primary" onClick={handleOpenAdd}>
          <Plus size={16} /> Add Quiz
        </button>
      </div>

      <div className="card" style={{ marginBottom: '1.5rem' }}>
        <div style={{ position: 'relative', maxWidth: '300px' }}>
          <Search size={18} style={{ position: 'absolute', left: '0.75rem', top: '50%', transform: 'translateY(-50%)', color: 'var(--text-muted)' }} />
          <input 
            type="text" 
            className="form-input" 
            style={{ paddingLeft: '2.5rem' }} 
            placeholder="Search quizzes..." 
            value={search}
            onChange={handleSearch}
          />
        </div>
      </div>

      <div className="table-container">
        <table>
          <thead>
            <tr>
              <th>Title</th>
              <th>Teacher</th>
              <th>Subject</th>
              <th>Grade</th>
              <th>Type</th>
              <th style={{ textAlign: 'right' }}>Actions</th>
            </tr>
          </thead>
          <tbody>
            {loading ? (
              <tr><td colSpan="6" style={{ textAlign: 'center', padding: '2rem' }}>Loading...</td></tr>
            ) : quizzes.length === 0 ? (
              <tr><td colSpan="6" style={{ textAlign: 'center', padding: '2rem', color: 'var(--text-muted)' }}>No quizzes found.</td></tr>
            ) : (
              quizzes.map(quiz => (
                <tr key={quiz.id}>
                  <td style={{ fontWeight: 600 }}>{quiz.title}</td>
                  <td>{quiz.teacher ? `${quiz.teacher.first_name} ${quiz.teacher.last_name}` : `ID: ${quiz.teacher_id}`}</td>
                  <td>{quiz.subject ? quiz.subject.name : `ID: ${quiz.subject_id}`}</td>
                  <td>{quiz.grade}</td>
                  <td>
                    {quiz.quiz_type === 'manual_mcq' ? (
                      <span style={{ display: 'inline-flex', alignItems: 'center', gap: '0.25rem', background: '#dbeafe', color: '#1e40af', padding: '0.25rem 0.5rem', borderRadius: '1rem', fontSize: '0.75rem', fontWeight: 600 }}>
                        <FileCheck size={14} /> Manual
                      </span>
                    ) : (
                      <span style={{ display: 'inline-flex', alignItems: 'center', gap: '0.25rem', background: '#fce7f3', color: '#be185d', padding: '0.25rem 0.5rem', borderRadius: '1rem', fontSize: '0.75rem', fontWeight: 600 }}>
                        <File size={14} /> PDF Upload
                      </span>
                    )}
                  </td>
                  <td style={{ textAlign: 'right' }}>
                    <button className="btn btn-secondary" style={{ padding: '0.25rem', marginRight: '0.5rem' }} onClick={() => handleOpenEdit(quiz)}>
                      <Edit2 size={16} />
                    </button>
                    <button className="btn btn-danger" style={{ padding: '0.25rem' }} onClick={() => handleDelete(quiz.id)}>
                      <Trash2 size={16} />
                    </button>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      {pagination.last_page > 1 && (
        <div className="pagination">
          <button 
            className="pagination-btn" 
            disabled={page === 1}
            onClick={() => setPage(prev => prev - 1)}
          ><ChevronLeft size={16} /></button>
          
          <span className="pagination-info">Page {page} of {pagination.last_page}</span>
          
          <button 
            className="pagination-btn" 
            disabled={page === pagination.last_page}
            onClick={() => setPage(prev => prev + 1)}
          ><ChevronRight size={16} /></button>
        </div>
      )}

      <Form 
        isOpen={isFormOpen} 
        onClose={() => setIsFormOpen(false)} 
        onSubmit={handleFormSubmit}
        initialData={editingQuiz}
      />
    </div>
  );
};

export default List;
