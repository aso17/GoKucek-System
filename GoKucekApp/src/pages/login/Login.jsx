import { useContext, useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import { ProjectContext } from "../../context/ProjectContext";
import { useAuth } from "../../context/AuthContext";
import LoadingDots from "../../components/common/LoadingDots";
import AppHead from "../../components/common/AppHead";
import Toast from "../../components/common/CenterToast";
import LoginForm from "./LoginForm";

export default function Login() {
  const { project, loading } = useContext(ProjectContext);
  const { login: loginFromContext } = useAuth();
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [toastMessage, setToastMessage] = useState("");
  const [toastType, setToastType] = useState("success");
  const navigate = useNavigate();

  const handleLogin = async (values) => {
    setIsSubmitting(true);
    try {
      await loginFromContext(values);
      navigate("/dashboard");
    } catch (error) {
      const message =
        error.response?.data?.message || error.message || "Login gagal";
      setToastType("error");
      setToastMessage(message);
    } finally {
      setIsSubmitting(false);
    }
  };

  if (loading) return <LoadingDots />;

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-sky-50 via-white to-emerald-50 px-4">
      <AppHead title={`Login | ${project?.name}`} />

      <Toast
        message={toastMessage}
        type={toastType}
        onClose={() => setToastMessage("")}
      />

      <div className="w-full max-w-md">
        <div className="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 md:p-10">
          {/* Logo */}
          <div className="flex justify-center mb-6">
            <img
              src={project?.logo_path}
              alt={project?.name}
              className="h-20 md:h-24 w-auto object-contain"
            />
          </div>

          {/* Heading */}
          <div className="text-center mb-8">
            <h1 className="text-2xl font-bold text-slate-800">
              Selamat Datang 👋
            </h1>
            <p className="text-sm text-slate-500 mt-2">
              Masuk ke sistem{" "}
              <span className="font-semibold">{project?.name}</span>
            </p>
          </div>

          {/* Form */}
          <LoginForm
            project={project}
            isSubmitting={isSubmitting}
            onSubmit={handleLogin}
          />

          {/* Links */}
          <div className="mt-8 pt-6 border-t border-slate-100 flex justify-between text-sm">
            <Link
              to="/forgot-password"
              className="text-slate-400 hover:text-slate-600 transition"
            >
              Lupa password?
            </Link>

            <Link
              to="/register"
              className="font-semibold hover:opacity-80 transition"
              style={{ color: project?.primary_color }}
            >
              Buat Akun
            </Link>
          </div>
        </div>

        <footer className="mt-10 text-center text-xs text-slate-400">
          © {new Date().getFullYear()} {project?.name} • Powered by{" "}
          <b>GoKucek</b>
        </footer>
      </div>
    </div>
  );
}
