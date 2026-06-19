import api from '../../api/axios';

export const getQuizzes = async (page = 1, search = '') => {
  const response = await api.get(`/api/quizzes?page=${page}&search=${search}`);
  return response.data;
};

export const createQuiz = async (data) => {
  // If it's a FormData object (for PDF upload), axios automatically sets Content-Type to multipart/form-data
  const response = await api.post('/api/quizzes', data);
  return response.data;
};

export const updateQuiz = async (id, data) => {
  // For PUT with FormData in Laravel, we typically need to spoof it with POST and _method=PUT
  // OR we can just use PUT if it's JSON (manual_mcq).
  if (data instanceof FormData) {
    data.append('_method', 'PUT');
    const response = await api.post(`/api/quizzes/${id}`, data);
    return response.data;
  } else {
    const response = await api.put(`/api/quizzes/${id}`, data);
    return response.data;
  }
};

export const deleteQuiz = async (id) => {
  const response = await api.delete(`/api/quizzes/${id}`);
  return response.data;
};
