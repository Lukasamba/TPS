using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace TPS.Data.Model
{
    public class User
    {
        [MaxLength(64)]
        public string Id { get; set; }
        [MaxLength(256)]
        public string Email { get; set; }
        [MaxLength(256)]
        public string Name { get; set; }
        public virtual HashSet<TeamMember>? Memberships { get; set; }

        public User(string id, string email, string name){
            Id = id;
            Email = email;
            Name = name;
        }
        
    }

}