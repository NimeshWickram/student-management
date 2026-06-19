import api from '../../api/axios';

export const getSubjects = async (page = 1, search = '') => {
  const response = await api.get(`/api/subjects?page=${page}&search=${search}`);
  return response.data;
};

export const createSubject = async (data) => {
  const response = await api.post('/api/subjects', data);
  return response.data;
};

export const updateSubject = async (id, data) => {
  const response = await api.put(`/api/subjects/${id}`, data);
  return response.data;
};

export const deleteSubject = async (id) => {
  const response = await api.delete(`/api/subjects/${id}`);
  return response.data;
};

export const getAllSubjectsList = async () => {
  // Useful for populating dropdowns (e.g. in Quizzes)
  const response = await api.get('/api/subjects');
  // Handle pagination or non-pagination based on how the backend returns data
  return response.data.data ? response.data.data : response.data; 
};
