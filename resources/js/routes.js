import teacherRoutes from './views/teacher';
import itemClassRoutes from './views/itemClass';
import itemTypeRoutes from './views/itemType';
import itemTypePriceRoutes from './views/itemTypePrice';
import itemRoutes from './views/item';
import courseRoutes from './views/course';
import bookCourseRoutes from './views/bookCourse';
import sourceRoutes from './views/source';
import programRoutes from './views/program';
import studentRoutes from './views/student';
import feeRoutes from './views/fee';
import subjectRoutes from './views/subject';
import examRoutes from './views/exam';
import ticketRoutes from './views/ticket';
import settingRoutes from './views/settings';
import itemBlockRoutes from './views/itemBlock';

export default [
    ...teacherRoutes,
    ...itemClassRoutes,
    ...itemTypeRoutes,
    ...itemTypePriceRoutes,
    ...itemRoutes,
    ...courseRoutes,
    ...bookCourseRoutes,
    ...sourceRoutes,
    ...programRoutes,
    ...studentRoutes,
    ...feeRoutes,
    ...subjectRoutes,
    ...examRoutes,
    ...ticketRoutes,
    ...settingRoutes,
    ...itemBlockRoutes
]