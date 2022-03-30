using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace TPS.Data.Model
{
    public class TeamMember
    {
        [MaxLength(64)]
        public string UserId { get; set; }
        public Guid TeamId { get; set; }
        [MaxLength]
        public Boolean IsTeamLead { get; set; }
        public virtual Team? Team { get; set; }
        public virtual User? User { get; set; }
        
        public TeamMember(string userid, Guid teamid){
            UserId = userid;
            TeamId = teamid;
        }
    }
}